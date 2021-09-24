<?php

namespace App\Http\Controllers;

use App\Models\PrintProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{

    public function index()
    {
        $new_orders = Order::count();
        $designs = 0;
        $requested = 0;
        $approved = Order::where('status', 'approved')->count();

        return view('superadmin.home')->with([
            'new_orders' => $new_orders,
            'designs' => $designs,
            'requested' => $requested,
            'approved' => $approved
        ]);
    }

    public function orderDetail($id)
    {
        $order = Order::find($id);
        if ($order) {
            return view('superadmin.order-details', compact('order'));
        }
        abort(404);
    }

    public function orders(Request  $request)
    {
        $admin = Auth::user();
        $status = null;
        $orders = Order::query();
        if ($request->input('status')) {
            $status = $request->input('status');
        }
        if ($status == 'cancelled') {
            $orders = $orders->whereNotNull('cancelled_at')->paginate(30);
        } else {
            $orders = $orders->where('fulfillment_status', $status)->whereNull('cancelled_at')->paginate(30);
        }
        $orders->append(['status' => $request->input('status')]);
        return view('superadmin.order', compact('orders', 'admin'));
    }

    public function stores()
    {
        $stores = User::where('role', 'store')->select('id', 'name', 'created_at')->orderby('created_at', 'desc')->paginate(20);
        return view('superadmin.stores', compact('stores'));
    }

    public function products()
    {
        $products = PrintProduct::with(['has_StoreProduct', 'hasSale'])->orderby('created_at', 'desc')->paginate(10);
        return view('superadmin.products', compact('products'));
    }

    public function productsCreate()
    {
        return view('superadmin.productcreate');
    }

    public function productsSave(Request  $request)
    {
        $product = new PrintProduct();
        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->shippingdetails = $request->input('shippingdetails');
        $product->designtemplate = $request->input('designtemplate');
        $product->sizeguide = $request->input('sizeguide');
        $product->sizes = $request->input('sizes');
        $product->price = $request->input('price');
        $product->thumbnail = Storage::disk('public')->put('product/images', $request->file('thumbnail'));
        $images = [];
        foreach ($request->file('images') as $file) {
            array_push($images, Storage::disk('public')->put('product/images', $file));
        }
        $product->images = json_encode($images);
        $mockups = [];
        $artworks = [];
        if ($request->file('mockups')) {
            $product->mockups = Storage::disk('public')->put('product/images', $request->file('mockups'));
        }

        if ($request->file('artworks')) {
            $product->artworks = Storage::disk('public')->put('product/images', $request->file('artworks'));
        }
        $product->save();
        return redirect()->route('admin.products');
    }

    public function productDelete($id)
    {
        PrintProduct::find($id)->delete();
        return redirect()->back()->with('success', 'Product is being deleted');
    }

    public function productEdit($id)
    {
        $product = PrintProduct::find($id);
        if ($product) {
            return view('superadmin.productedit', compact('product'));
        } else abort(404);
    }


    public function productUpdate($id, Request  $request)
    {
        $product = PrintProduct::find($id);
        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->shippingdetails = $request->input('shippingdetails');
        $product->designtemplate = $request->input('designtemplate');
        $product->sizeguide = $request->input('sizeguide');
        $product->sizes = $request->input('sizes');
        $product->price = $request->input('price');
        if ($request->file('thumbnail')) {
            if (Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $product->thumbnail = Storage::disk('public')->put('product/images', $request->file('thumbnail'));
        }

        $images = [];
        $mockups = [];
        $artworks = [];
        if ($request->file('images')) {
            foreach ($product->images as $deleteImage) {
                if (Storage::disk('public')->exists($deleteImage)) {
                    Storage::disk('public')->delete($deleteImage);
                }
            }



            foreach ($request->file('images') as $file) {
                array_push($images, Storage::disk('public')->put('product/images', $file));
            }
            $product->images = json_encode($images);
        }

        if ($request->file('mockups')) {
            if (Storage::disk('public')->exists($product->mockups)) {
                Storage::disk('public')->delete($product->mockups);
            }

            $product->mockups = Storage::disk('public')->put('product/images', $request->file('mockups'));
        }

        if ($request->file('artworks')) {
            if (Storage::disk('public')->exists($product->artworks)) {
                Storage::disk('public')->delete($product->artworks);
            }

            $product->artworks = Storage::disk('public')->put('product/images', $request->file('artworks'));
        }


        $product->save();
        return redirect()->back();
    }
}
