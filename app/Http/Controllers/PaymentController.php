<?php

namespace App\Http\Controllers;

use App\Models\BillingDetail;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function deductCommission($products, $shop)
    {
        $amount = 0;
        foreach ($products as $product) {
            $amount += $product->sale;
        }
        $billingDetail = BillingDetail::where('shop_id', $shop->id)->first();
        if ($billingDetail !== null) {
            if ($billingDetail->stripe_customer_id !== null) {
                $stripe = new StripeController();
                $stripe->payCharge($billingDetail, $amount);
                return true;
            } else {
                return true;
            }
        }

        return false;
    }
}
