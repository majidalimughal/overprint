<?php

namespace App\Http\Controllers;

use App\Models\BillingDetail;
use App\Models\PaymentCycle;
use App\Models\StripeCustomer;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Stripe\Stripe;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }



    public function saveCardDetails(Request $request)
    {
        $stripeCustomer = $this->createCustomer([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            // 'city' => $request->city,
            // 'zip' => $request->zip,
            // 'country' => $request->country
        ]);
        if ($stripeCustomer !== null) {
            $customer = BillingDetail::where('shop_id', Auth::id())->first();

            if ($customer === null) {
                $customer = new BillingDetail();
                $customer->shop_id = Auth::id();
                $customer->active_method = 'stripe';
            }
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->city = $request->city;
            $customer->country = $request->country;
            $customer->zip = $request->zip;
            $customer->stripe_customer_id = encrypt($stripeCustomer->id);
            $customer->stripe_payment_method_id = encrypt($request->payment_method);
            $customer->save();
            // dd($stripeCustomer);
            // $this->createPaymentIntent($customer, 5);
            return response()->json([
                'status' => true,
                'message' => 'Billing Method Added'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Something Went Wrong'
        ]);
    }


    public function createCustomer($customerDetail)
    {
        try {
            $customer = \Stripe\Customer::create([
                'name' => $customerDetail['name'],
                'email' => $customerDetail['email'],
                'payment_method' => $customerDetail['payment_method'],
                'phone' => $customerDetail['phone'],
                // 'address' => $customerDetail['address'],
                // 'payment_method' => $customerDetail['payment_method']
            ]);

            return $customer;
        } catch (Exception $ex) {
            // dd($ex);
        }
    }




    public function payCharge(object $customer, int $amount)
    {

        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys

        try {
            $charge = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'customer' => decrypt($customer->customer_id),
                'payment_method' => decrypt($customer->payment_method_id),
                'error_on_requires_action' => true,
                'confirm' => true,
            ]);

            return $charge;
        } catch (Exception $e) {
            return null;
            // Error code will be authentication_required if authentication is needed
        }
    }
}
