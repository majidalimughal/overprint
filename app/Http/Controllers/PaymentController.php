<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function handleCharge($order_id,$shop_id)
    {
        $order=Order::find($order_id);
        $shop=User::find($shop_id);

    }
}
