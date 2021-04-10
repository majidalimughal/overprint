<?php

namespace App\Http\Controllers;

use App\Models\PrintProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function synchronzeProducts()
    {
        $shop=Auth::user();
        $next='';
        $count=$shop->api()->rest('GET','/admin/products/count.json',[
            'created_at_min'=>'2010-01-01'
        ]);
        $count=$count['body']['count'];
        $count=ceil($count/250);
        for ($i=1;$i<=$count;$i++)
        {
            $products=$shop->api()->rest('GET','/admin/products.json',[
                'limit'=>250,
                'page_info'=>$next
            ]);
            if (!$products['errors'])
            {
                if (isset($products['link']['next']))
                {
                    $next=$products['link']['next'];
                }
                $products=$products['body']['products'];
                foreach ($products as $product)
                {
                    $this->CreateUpdateProduct($product,$shop->name);
                }
            }
        }

        return redirect()->back()->with('success','Products will be synchronized!');
    }
    public function CreateUpdateProduct($product,$shop,$print_id=null)
    {
        $product=json_decode(json_encode($product),FALSE);

        $Product=Product::where([
            'shop'=>$shop,
            'product_id'=>$product->id
        ])->first();

        if ($Product===null)
        {
            $Product=new Product();
            $Product->shop=$shop;
            $Product->product_id=$product->id;
        }

        if ($print_id!==null)
        {
            $Product->print_product_id=$print_id;
        }


        $Product->title=$product->title;
        $Product->vendor=$product->vendor;
        $Product->product_type=$product->product_type;
        $Product->body_html=$product->body_html;
        $Product->tags=$product->tags;
        $Product->options=json_encode($product->options);
        $Product->images=json_encode($product->images);
        if (isset($product->image->src))
        {
            $Product->image=$product->image->src;
        }
        $productVarinat=[];
        $PImages=json_decode(json_encode($product->images),true);
        foreach (json_decode(json_encode($product->variants),true) as $variant)
        {
            if(isset($variant['image_id']) && $variant['image_id']!==null)
            {
                $image=collect($PImages)->where('id',$variant['image_id'])->first();
                if ($image!==null)
                {
                    $variant['image']=$image['src'];
                }
            }else if(isset($product->image->src) && $product->image->src!==null)
            {
                $variant['image']=$product->image->src;
            }
            else
            {
                $variant['image']=null;
            }
            array_push($productVarinat,$variant);
        }
        $variants=json_decode(json_encode($productVarinat),FALSE);

        $Product->variants = json_encode($variants);

        $Product->save();
    }
    public function index(Request  $request)
    {
        $products = Product::with('has_print_product')->where('shop', Auth::user()->name);
        if ($request->input('build'))
        {
            $products=$products->whereHas('has_print_product');
        }
        if ($request->input('search'))
        {
            $products=$products->where('title','like','%'.$request->input('search').'%')
                ->orWhere('vendor','like','%'.$request->input('search').'%')
                ->orWhere('product_type','like','%'.$request->input('search').'%')
                ->orWhere('tags','like','%'.$request->input('search').'%');
        }


        $products=$products->paginate(20);
        if ($request->input('search'))
        {
            $products->appends(['search'=>$request->input('search')]);
        }
        if ($request->input('build'))
        {
            $products->appends(['build'=>$request->input('build')]);
        }
        return view('store.shopify_products', compact('products'));
    }


    public function availableProducts(Request  $request)
    {
        $ids=DB::table('products')->where('shop', Auth::user()->name)->pluck('print_product_id')->toArray();
        $products = PrintProduct::whereNotIn('id',$ids)->paginate(20);
        return view('store.build_products',compact('products'));
    }

    public function productDetail($id)
    {
        $product=Product::with('has_print_product')->where([
            'id'=>decrypt($id),
            'shop'=>Auth::user()->name
        ])->first();
        if ($product===null)
        {
            abort(404);
        }
        return view('store.productDetail',compact('product'));
    }
}
