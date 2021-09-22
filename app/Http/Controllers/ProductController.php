<?php

namespace App\Http\Controllers;

use App\Models\PrintProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function synchronzeProducts()
    {
        $shop = Auth::user();
        $next = '';
        $count = $shop->api()->rest('GET', '/admin/products/count.json', [
            'created_at_min' => '2010-01-01'
        ]);
        $count = $count['body']['count'];
        $count = ceil($count / 250);
        for ($i = 1; $i <= $count; $i++) {
            $products = $shop->api()->rest('GET', '/admin/products.json', [
                'limit' => 250,
                'page_info' => $next
            ]);
            if (!$products['errors']) {
                if (isset($products['link']['next'])) {
                    $next = $products['link']['next'];
                }
                $products = $products['body']['products'];
                foreach ($products as $product) {
                    $this->CreateUpdateProduct($product, $shop->name);
                }
            }
        }

        return redirect()->back()->with('success', 'Products will be synchronized!');
    }
    public function CreateUpdateProduct($product, $shop, $print_id = null, array $artworks = null, array $mockups = null)
    {
        $product = json_decode(json_encode($product), FALSE);

        $Product = Product::where([
            'shop' => $shop,
            'product_id' => $product->id
        ])->first();

        if ($Product === null) {
            $Product = new Product();
            $Product->shop = $shop;
            $Product->product_id = $product->id;
        }

        if ($print_id !== null) {
            $Product->print_product_id = $print_id;
        }


        $Product->title = $product->title;
        $Product->vendor = $product->vendor;
        $Product->product_type = $product->product_type;
        $Product->body_html = $product->body_html;
        $Product->tags = $product->tags;
        $Product->options = json_encode($product->options);
        $Product->images = json_encode($product->images);
        if ($artworks !== null) {
            $Product->artworks = json_encode($artworks);
        }
        if ($mockups !== null) {
            $Product->mockups = json_encode($mockups);
        }
        if (isset($product->image->src)) {
            $Product->image = $product->image->src;
        }
        $productVarinat = [];
        $PImages = json_decode(json_encode($product->images), true);
        foreach (json_decode(json_encode($product->variants), true) as $variant) {
            if (isset($variant['image_id']) && $variant['image_id'] !== null) {
                $image = collect($PImages)->where('id', $variant['image_id'])->first();
                if ($image !== null) {
                    $variant['image'] = $image['src'];
                }
            } else if (isset($product->image->src) && $product->image->src !== null) {
                $variant['image'] = $product->image->src;
            } else {
                $variant['image'] = null;
            }
            array_push($productVarinat, $variant);
        }
        $variants = json_decode(json_encode($productVarinat), FALSE);

        $Product->variants = json_encode($variants);

        $Product->save();
        return $Product;
    }
    public function index(Request  $request)
    {
        $products = Product::with('has_print_product')->where('shop', Auth::user()->name);
        if ($request->input('build')) {
            $products = $products->whereHas('has_print_product');
        }
        if ($request->input('search')) {
            $products = $products->where('title', 'like', '%' . $request->input('search') . '%')
                ->orWhere('vendor', 'like', '%' . $request->input('search') . '%')
                ->orWhere('product_type', 'like', '%' . $request->input('search') . '%')
                ->orWhere('tags', 'like', '%' . $request->input('search') . '%');
        }


        $products = $products->paginate(20);
        if ($request->input('search')) {
            $products->appends(['search' => $request->input('search')]);
        }
        if ($request->input('build')) {
            $products->appends(['build' => $request->input('build')]);
        }
        return view('store.shopify_products', compact('products'));
    }


    public function availableProducts(Request  $request)
    {
        $ids = DB::table('products')->where('shop', Auth::user()->name)->pluck('print_product_id')->toArray();
        $products = PrintProduct::whereNotIn('id', array_filter($ids))->paginate(20);

        return view('store.build_products', compact('products'));
    }

    public function productDetail($id)
    {
        $product = Product::with('has_print_product')->where([
            'id' => decrypt($id),
            'shop' => Auth::user()->name
        ])->first();
        if ($product === null) {
            abort(404);
        }
        return view('store.productDetail', compact('product'));
    }


    public function createShopifyProduct($id)
    {
        $id = decrypt($id);
        $product = PrintProduct::find($id);
        if ($product !== null) {
            return view('store.create_product', compact('product'));
        }
        abort(404);
    }


    public function storeShopifyProduct($id, Request $request)
    {
        // dd($request->all());



        $shop = Auth::user();

        if ($shop->billingDetails === null) {
            return back()->with('error', 'Please add billing details');
        }
        $variantsArray = $this->makeVariants($request);
        $optionsArray = $this->makeOptions($request);
        $imagesArray = $this->makeImages($request);

        $artworks = $this->storeImages($request->file('artworks'));
        $mockups = $this->storeImages($request->file('mockups'));
        $product = $shop->api()->rest('POST', '/admin/products.json', [
            'product' => [
                "title" => $request->title,
                "body_html" => $request->description,
                "vendor" => $request->vendor,
                "tags" => $request->tags,
                "product_type" => $request->product_type,
                "variants" => $variantsArray,
                "options" => $optionsArray,
                "images" => $imagesArray,
                "status" =>  $request->status == 1 ? 'active' : 'draft'
            ]
        ]);

        if (isset($product['body']['product'])) {
            $pro = $this->CreateUpdateProduct($product['body']['product'], $shop->name, $id, $artworks, $mockups);
            return redirect()->route('shopify.product.detail', encrypt($pro->id))->with('success', 'Product Created');
        } else {
            return back()->with('error', 'Something went wrong');
        }
    }


    public function makeImages($request)
    {
        $images = [];
        foreach ($request->file('mockups') as $image) {
            $image = Storage::disk('public')->put('uploads', $image);
            array_push($images, [
                'src' => asset($image)
            ]);
        }
        return $images;
    }


    public function makeOptions($request)
    {
        $options = [];

        if ($request->attribute1 !== null) {
            array_push($options, [
                'name' => $request->attribute1,
                'values' => explode(',', $request->option1)
            ]);
        }

        if ($request->attribute2 !== null) {
            array_push($options, [
                'name' => $request->attribute2,
                'values' => explode(',', $request->option2)
            ]);
        }

        if ($request->attribute3 !== null) {
            array_push($options, [
                'name' => $request->attribute3,
                'values' => explode(',', $request->option3)
            ]);
        }

        return $options;
    }



    public function makeVariants($request)
    {
        $variants_array = [];
        $titles = $request->variant_title;
        $prices = $request->variant_price;
        $costs = $request->variant_cost;
        $quantities = $request->variant_quantity;
        $skus = $request->variant_sku;
        $barcodes = $request->variant_barcode;
        foreach ($titles as $index => $title) {
            $options = explode('/', $title);
            array_push($variants_array, [
                'title' => $title,
                'sku' => $skus[$index],
                'option1' => isset($options[0]) ? $options[0] : null,
                'option2' => isset($options[1]) ? $options[1] : null,
                'option3' => isset($options[2]) ? $options[2] : null,
                'inventory_quantity' => $quantities[$index],
                // 'grams' => $product->weight * 1000,
                // 'weight' => $product->weight,
                // 'weight_unit' => 'kg',
                'barcode' => $barcodes[$index],
                'price' => $prices[$index],
                'cost' => $costs[$index],
            ]);
        }
        return $variants_array;
    }


    public function downlodFiles($id, Request $request)
    {
        $printProduct = PrintProduct::find($id);
        if ($request->input('files')) {
            if ($request->input('files') == 'artwork') {
                return $this->zipfile($printProduct->artworks, 'artwork');
            } else {
                return $this->zipfile($printProduct->mockups, 'mockup');
            }
        }
    }


    public function zipfile($images, $name)
    {
        $zip_file = $name . '.zip'; // Name of our archive to download

        // Initializing PHP class
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);



        foreach ($images as $index => $image) {
            $imageName = explode('/', $image);
            $zip->addFile(public_path($image), $imageName[count($imageName) - 1]);
        }

        $zip->close();


        // We return the file immediately after download
        return response()->download($zip_file);
    }

    public function storeImages($files)
    {
        $images = [];
        foreach ($files as $file) {
            array_push($images, Storage::disk('public')->put('product/images', $file));
        }
        return $images;
    }
}
