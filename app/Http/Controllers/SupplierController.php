<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function synchronizeOrders()
    {
        $users=User::where('role','store')->get();
        $ordController=new OrderController();
        $date = Carbon::today()->subYear(10)->format('Y-m-d');
        foreach ($users as $shop)
        {
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
                                $ordController->CreateOrder($order, $shop->name);
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
                                $ordController->CreateOrder($order, $shop->name);
                            }

                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success','Orders are being synchronized');
    }
}
