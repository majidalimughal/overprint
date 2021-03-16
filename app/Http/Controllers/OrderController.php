<?php

namespace App\Http\Controllers;

use App\Models\LineItem;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Osiset\ShopifyApp\Storage\Models\Plan;

class OrderController extends Controller
{
    public function dashboard()
    {
        $shop=Auth::user();
        return redirect()->route('admin.orders');
    }

    public function orders(Request  $request)
    {
        $shop=Auth::user();
        $status=null;
        $orders=Order::where('shop',$shop->name)->newQuery();
        if ($request->input('status'))
        {
            $status=$request->input('status');
        }
        if($status=='cancelled')
        {
            $orders=$orders->whereNotNull('cancelled_at')->paginate(30);
        }
        else
        {
            $orders=$orders->where('fulfillment_status',$status)->whereNull('cancelled_at')->paginate(30);
        }
        $orders->append(['status'=>$request->input('status')]);
        return view('admin.order',compact('orders'));
    }
    public function SynchronizeOrders()
    {
        $shop = Auth::user();
        $date = Carbon::today()->subYear(10)->format('Y-m-d');
        $count = $shop->api()->rest('GET', '/admin/orders/count.json',[
            'status'=>'any',
            'created_at_min'=>$date
        ]);
        $count = floatval($count['body']['count']);
        $count = ceil($count / 250);
        $next = '';
        for ($i = 1; $i <= $count; ++$i) {

            if($i==1)
            {
                $orders = $shop->api()->rest('GET', '/admin/api/2020-04/orders.json', [
                    'limit' => 250,
                    'status'=>'any',
                    'created_at_min'=>$date
                ]);

                if (isset($orders['errors']) && !$orders['errors']) {
                    {
                        if(isset($orders['link']['next']))
                        {
                            $next = $orders['link']['next'];
                        }
                        $orders = $orders['body']['orders'];
                        foreach ($orders as $order) {
                            $this->CreateOrder($order, $shop->name);
                        }

                    }
                }
            }else
            {
                $orders = $shop->api()->rest('GET', '/admin/orders.json', [
                    'limit' => 250,
                    'page_info' => $next
                ]);
                if (isset($orders['errors']) && !$orders['errors']) {
                    {
                        $next = $orders['link']['next'];
                        $orders = $orders['body']['orders'];

                        foreach ($orders as $order) {
                            $this->CreateOrder($order, $shop->name);
                        }

                    }
                }
            }
        }



    }

    public function CreateOrder($order, $shop, $properties = null,$payment=null)
    {
        try {

            $shop = User::where('name', $shop)->first();
            $order = json_decode(json_encode($order, false));
            $ord = Order::where('shopify_order_id', $order->id)->where('shop', $shop->name)->first();
            if ($ord === null) {
                $ord = new Order();
                $ord->shop = $shop->name;
                $ord->shopify_order_id = $order->id;
            }
            $ord->name = $order->order_number;
            $ord->created_at=Carbon::createFromTimeString($order->created_at)->format('Y-m-d H:i:s');
            $ord->updated_at=Carbon::createFromTimeString($order->updated_at)->format('Y-m-d H:i:s');
            if($order->cancelled_at)
            {
                $ord->cancelled_at=Carbon::createFromTimeString($order->cancelled_at)->format('Y-m-d H:i:s');
                $ord->status='cancelled';
            }
            $ord->email = $order->email;
            $ord->price = $order->total_price;
            $ord->total_line_items_price = $order->subtotal_price;
            $ord->total_weight = $order->total_weight;
            $ord->total_tax = $order->total_tax;
            $ord->financial_status = $order->financial_status;
            $ord->fulfillment_status = $order->fulfillment_status;

            $ord->discount_amount = $order->total_discounts;
            if($payment!==null)
            {
                $ord->payment =json_encode($payment);
            }
            if (isset($order->shipping_address)) {
                $ord->shipping_address = json_encode($order->shipping_address);
            }
            if (isset($order->billing_address)) {
                $ord->billing_address = json_encode($order->billing_address);
            } else {
                if (isset($order->shipping_address)) {
                    $ord->billing_address = json_encode($order->shipping_address);
                }
            }
            $ord->shipping_lines = json_encode($order->shipping_lines);
            if ($properties !== null) {
                $ord->properties = json_encode($properties);
            }
            $ord->save();
            foreach ($order->line_items as $key => $line_item) {
                $lineItem = LineItem::where([
                    'order_id' => $ord->id,
                    'line_id' => $line_item->id
                ])->first();
                if ($lineItem === null) {
                    $lineItem = new LineItem();
                    $lineItem->order_id = $ord->id;
                    $lineItem->line_id = $line_item->id;
                }
                $lineItem->title = $line_item->name;
                $lineItem->quantity = $line_item->quantity;
                $lineItem->price = $line_item->price;
                $lineItem->variant_id = $line_item->variant_id;
                $lineItem->product_id = $line_item->product_id;
                $product = $shop->api()->rest('GET', '/admin/products/' . $line_item->product_id . '.json');
                if (!$product['errors']) {
                    $product = $product['body']['product'];
                    $product = json_decode(json_encode($product), FALSE);
                    $variants = collect($product->variants);
                    $variant = $variants->where('id', $line_item->variant_id)->first();
                    $options = [];
                    if (isset($variant->option1) && $variant->option1 !== null) {
                        array_push($options, $variant->option1);
                    }
                    if (isset($variant->option2) && $variant->option2 !== null) {
                        array_push($options, $variant->option2);
                    }
                    if (isset($variant->option3) && $variant->option3 !== null) {
                        array_push($options, $variant->option3);
                    }
                    $lineItem->options = json_encode($options);

                    if (isset($variant->image_id) && $variant->image_id !== null) {
                        $images = collect($product->images);
                        $image = $images->where('id', $variant->image_id)->first();
                        if ($image !== null) {
                            $lineItem->image = $image->src;
                        } else {
                            $lineItem->image = (isset($product->image->src) ? $product->image->src : asset('img/noimg.svg'));
                        }
                    } else {
                        $lineItem->image = (isset($product->image->src) ? $product->image->src : asset('img/noimg.svg'));
                    }

                }
                $lineItem->save();
            }

            return true;
        } catch (\Exception $exception) {
            dd($exception);
        }

    }


    public function orderDetail($id)
    {
        $order=Order::find($id);
        if ($order)
        {
            return view('admin.order-details',compact('order'));
        }
        abort(404);
    }

    public function plans()
    {
        $plans=Plan::all();
        $shop=Auth::user();
        return view('admin.pricing',compact('plans','shop'));
    }
}
