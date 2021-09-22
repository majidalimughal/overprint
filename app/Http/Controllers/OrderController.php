<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\LineItem;
use Illuminate\Http\Request;
use App\Models\OrderFulfillment;
use App\Models\FulfillmentLineItem;
use App\Models\Product;
use App\Models\ProductSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Osiset\ShopifyApp\Storage\Models\Plan;

class OrderController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('admin.orders');
    }

    public function orders(Request  $request)
    {
        $shop = Auth::user();
        $status = null;
        $orders = Order::where('shop', $shop->name)->newQuery();
        if ($request->input('status')) {
            $status = $request->input('status');
        }
        if ($status == 'cancelled') {
            $orders = $orders->whereNotNull('cancelled_at')->paginate(30);
        } else {
            $orders = $orders->where('fulfillment_status', $status)->whereNull('cancelled_at')->paginate(30);
        }
        $orders->append(['status' => $request->input('status')]);
        return view('admin.order', compact('orders'));
    }
    public function SynchronizeOrders()
    {
        $shop = Auth::user();
        $date = Carbon::today()->subYear(1)->format('Y-m-d');
        // dd($shop);
        $count = $shop->api()->rest('GET', '/admin/orders/count.json', [
            'status' => 'any',
            'created_at_min' => $date
        ]);
        $count = floatval($count['body']['count']);
        // dd($count);
        $count = ceil($count / 250);
        $next = '';
        for ($i = 1; $i <= $count; ++$i) {

            if ($i == 1) {
                $orders = $shop->api()->rest('GET', '/admin/orders.json', [
                    'limit' => 250,
                    'status' => 'any',
                    'created_at_min' => $date
                ]);
                // dd($orders);

                if (isset($orders['errors']) && !$orders['errors']) { {
                        if (isset($orders['link']['next'])) {
                            $next = $orders['link']['next'];
                        }
                        $orders = $orders['body']['orders'];
                        foreach ($orders as $order) {
                            $this->CreateOrder($order, $shop->name);
                        }
                    }
                }
            } else {
                $orders = $shop->api()->rest('GET', '/admin/orders.json', [
                    'limit' => 250,
                    'page_info' => $next
                ]);
                if (isset($orders['errors']) && !$orders['errors']) { {
                        $next = $orders['link']['next'];
                        $orders = $orders['body']['orders'];

                        foreach ($orders as $order) {
                            $this->CreateOrder($order, $shop->name);
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Orders are being synchronized');
    }

    public function CreateOrder($order, $shop, $properties = null, $payment = null)
    {

        $CatalogProducts = [];
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
            $ord->created_at = Carbon::createFromTimeString($order->created_at)->format('Y-m-d H:i:s');
            $ord->updated_at = Carbon::createFromTimeString($order->updated_at)->format('Y-m-d H:i:s');
            if ($order->cancelled_at) {
                $ord->cancelled_at = Carbon::createFromTimeString($order->cancelled_at)->format('Y-m-d H:i:s');
                $ord->status = 'cancelled';
            }
            $ord->email = $order->email;
            $ord->price = $order->total_price;
            $ord->total_line_items_price = $order->subtotal_price;
            $ord->total_weight = $order->total_weight;
            $ord->total_tax = $order->total_tax;
            $ord->financial_status = $order->financial_status;
            $ord->fulfillment_status = $order->fulfillment_status;

            $ord->discount_amount = $order->total_discounts;
            if ($payment !== null) {
                $ord->payment = json_encode($payment);
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
                $lineItem->fulfillable_quantity = $line_item->quantity;
                $lineItem->fulfillment_status = 'unfulfilled';
                $lineItem->price = $line_item->price;
                $lineItem->variant_id = $line_item->variant_id;
                $lineItem->product_id = $line_item->product_id;

                // check if this product exists in print products catalog then we will create an entry in product sales

                $printProduct = Product::where('product_id', $line_item->product_id)->first();

                if ($printProduct->print_product_id !== null) {
                    $sale = ProductSale::create([
                        'order_id' => $ord->id,
                        'product_id' => $printProduct->id,
                        'print_product_id' => $printProduct->print_product_id,
                        'sale' => $line_item->price
                    ]);

                    array_push($CatalogProducts, $sale);
                }

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
            $paymentController = new PaymentController();
            $paymentResponse = $paymentController->deductCommission($CatalogProducts, $shop);
            return true;
        } catch (\Exception $exception) {
            Storage::put('Exception.txt', $exception->getMessage());
        }
    }





    public function orderDetail($id)
    {
        $order = Order::find($id);
        if ($order) {
            return view('admin.order-details', compact('order'));
        }
        abort(404);
    }

    public function orderFulfillment($id)
    {
        $order = Order::find($id);
        if ($order) {
            return view('admin.order-fulfillment', compact('order'));
        }
        abort(404);
    }

    public function plans()
    {
        $plans = Plan::all();
        $shop = Auth::user();
        return view('admin.pricing', compact('plans', 'shop'));
    }

    public function processOrderFulfillment(Request $request, $id)
    {
        $order = Order::find($id);
        $admin = Auth::user();

        if ($order != null) {
            if ($order->financial_status == 'paid') {
                $fulfillable_quantities = $request->input('item_fulfill_quantity');


                $shop = User::where('name', $order->shop)->first();
                Auth::login($shop);

                $shopify_fulfillment = null;
                if ($shop != null) {
                    $location_response = json_decode(json_encode($shop->api()->rest('GET', '/admin/locations.json')));

                    if (!$location_response->errors) {

                        foreach ($location_response->body->locations as $location) {
                            if ($location->name == "IZIKI 1 BLOC 25, NUMERO 165") {
                                $data = [
                                    "fulfillment" => [
                                        "location_id" => $location->id,
                                        "tracking_number" => null,
                                        "notify_customer" => false,
                                        "line_items" => []
                                    ]
                                ];
                            }
                        }

                        foreach ($request->input('item_id') as $index => $item) {
                            $line_item = LineItem::find($item);
                            if ($line_item != null && $fulfillable_quantities[$index] > 0) {
                                array_push($data['fulfillment']['line_items'], [
                                    "id" => $line_item->line_id,
                                    "quantity" => $fulfillable_quantities[$index],
                                ]);
                            }
                        }


                        $response = json_decode(json_encode($shop->api()->rest('POST', '/admin/orders/' . $order->shopify_order_id . '/fulfillments.json', $data)));

                        if ($response->errors) {
                            if (strpos($response->body->base[0], "already fulfilled") !== false) {
                                $res = json_decode(json_encode($shop->api()->rest('GET', '/admin/orders/' . $order->shopify_order_id . '/fulfillments.json')));
                                Auth::login($admin);
                                return $this->set_fulfilments_for_already_fulfilled_order($request, $id, $fulfillable_quantities, $order, $res);
                            }
                            Auth::login($admin);
                            return redirect()->back()->with('error', 'Cant Fulfill Items of Order in Related Store!');
                        } else {
                            Auth::login($admin);
                            return $this->set_fulfilments($request, $id, $fulfillable_quantities, $order, $response);
                        }
                    } else {
                        Auth::login($admin);
                        return redirect()->back()->with('error', 'Cant Fulfill Item Cause Related Store Dont have Location Stored!');
                    }
                } else {
                    Auth::login($admin);
                    return redirect()->back()->with('error', 'Order Related Store Not Found');
                }
            } else {
                Auth::login($admin);
                return redirect()->back()->with('error', 'Refunded Order Cant Be Processed Fulfillment');
            }
        } else {
            Auth::login($admin);
            return redirect()->back()->with('error', 'Order Not Found To Process Fulfillment');
        }
    }


    public function set_fulfilments(Request $request, $id, $fulfillable_quantities, $order, $response): RedirectResponse
    {
        foreach ($request->input('item_id') as $index => $item) {
            $line_item = LineItem::find($item);
            if ($line_item != null && $fulfillable_quantities[$index] > 0) {
                if ($fulfillable_quantities[$index] == $line_item->fulfillable_quantity) {
                    $line_item->fulfillment_status = 'fulfilled';
                } else if ($fulfillable_quantities[$index] < $line_item->fulfillable_quantity) {
                    $line_item->fulfillment_status = 'partially-fulfilled';
                }
                $line_item->fulfillable_quantity = $line_item->fulfillable_quantity - $fulfillable_quantities[$index];
            }
            $line_item->save();
        }
        $order->fulfillment_status = $order->getStatus($order);
        $order->save();

        $fulfillment = new OrderFulfillment();
        $fulfillment->fulfillment_shopify_id = $response->body->fulfillment->id;
        $fulfillment->name = $response->body->fulfillment->name;
        $fulfillment->order_id = $order->id;
        $fulfillment->status = 'fulfilled';
        $fulfillment->save();

        foreach ($request->input('item_id') as $index => $item) {
            if ($fulfillable_quantities[$index] > 0) {
                $fulfillment_line_item = new FulfillmentLineItem();
                $fulfillment_line_item->fulfilled_quantity = $fulfillable_quantities[$index];
                $fulfillment_line_item->order_fulfillment_id = $fulfillment->id;
                $fulfillment_line_item->order_line_item_id = $item;
                $fulfillment_line_item->save();
            }
        }

        return redirect()->back()->with('success', 'Order Line Items Marked as Fulfilled Successfully!');
    }

    public function set_fulfilments_for_already_fulfilled_order(Request $request, $id, $fulfillable_quantities, $order, $response): RedirectResponse
    {
        foreach ($request->input('item_id') as $index => $item) {
            $line_item = LineItem::find($item);
            if ($line_item != null && $fulfillable_quantities[$index] > 0) {
                if ($fulfillable_quantities[$index] == $line_item->fulfillable_quantity) {
                    $line_item->fulfillment_status = 'fulfilled';
                } else if ($fulfillable_quantities[$index] < $line_item->fulfillable_quantity) {
                    $line_item->fulfillment_status = 'partially-fulfilled';
                }
                $line_item->fulfillable_quantity = $line_item->fulfillable_quantity - $fulfillable_quantities[$index];
            }
            $line_item->save();
        }
        $order->fulfillment_status = 'fulfilled';
        $order->save();


        $fulfillment = new OrderFulfillment();
        $fulfillment->fulfillment_shopify_id = $response->body->fulfillments[0]->id;
        $fulfillment->name = $response->body->fulfillments[0]->name;
        $fulfillment->order_id = $order->id;
        $fulfillment->status = 'fulfilled';
        $fulfillment->save();

        foreach ($request->input('item_id') as $index => $item) {
            if ($fulfillable_quantities[$index] > 0) {
                $fulfillment_line_item = new FulfillmentLineItem();
                $fulfillment_line_item->fulfilled_quantity = $fulfillable_quantities[$index];
                $fulfillment_line_item->order_fulfillment_id = $fulfillment->id;
                $fulfillment_line_item->order_line_item_id = $item;
                $fulfillment_line_item->save();
            }
        }

        return redirect()->back()->with('success', 'Order Line Items Marked as Fulfilled Manually Successfully!');
    }



    public function addOrderTracking(Request $request)
    {

        $order = Order::find($request->id);
        $admin = Auth::user();
        $shop = User::where('name', $order->shop)->first();
        Auth::login($shop);

        if ($order != null) {

            $fulfillments = $request->input('fulfillment');
            $tracking_numbers = $request->input('tracking_number');
            $tracking_urls = $request->input('tracking_url');
            $tracking_notes = $request->input('tracking_notes');


            if ($shop != null) {
                foreach ($fulfillments as $index => $f) {
                    $current = OrderFulfillment::find($f);
                    if ($current != null) {
                        $data = [
                            "fulfillment" => [
                                "tracking_number" => $tracking_numbers[$index],
                                "tracking_url" => $tracking_urls[$index],
                                "notify_customer" => false,
                            ]
                        ];

                        $response = json_decode(json_encode($shop->api()->rest('PUT', '/admin/orders/' . $order->shopify_order_id . '/fulfillments/' . $current->fulfillment_shopify_id . '.json', $data)));

                        if (!$response->errors) {
                            $current->tracking_number = $tracking_numbers[$index];
                            $current->tracking_url = $tracking_urls[$index];
                            $current->tracking_notes = $tracking_notes[$index];

                            $current->save();
                            $this->CompleteFullFillment($current, $shop);
                        }
                    }
                }
            } else {
                Auth::login($admin);
                return redirect()->back()->with('error', 'Order Related Store Not Found');
            }
            Auth::login($admin);
            return redirect()->back()->with('success', 'Tracking Details Added To Fulfillment Successfully!');
        } else {
            Auth::login($admin);
            return redirect()->back()->with('error', 'Order Not Found To Add Tracking In Fulfillment');
        }
    }


    public function CompleteFullFillment($orderFullfillment, $shop)
    {
        $order = Order::where('id', $orderFullfillment->order_id)->first();
        if ($orderFullfillment->fulfillment_shopify_id) {
            $shop->api()->rest('POST', '/admin/orders/' . $order->shopify_order_id . '/fulfillments/' . $orderFullfillment->fulfillment_shopify_id . '/complete.json');
        }
    }
}
