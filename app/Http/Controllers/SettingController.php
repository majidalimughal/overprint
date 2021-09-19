<?php

namespace App\Http\Controllers;

use App\Models\BillingDetail;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function billingMethods()
    {
        $shop = Auth::user();
        $setting = Setting::where('shop', $shop->name)->first();
        $billing = BillingDetail::where('shop_id', $shop->id)->first();
        if ($setting === null) {
            $setting = new Setting();
            $setting->shop = $shop->name;
            $setting->save();
        }
        return view('admin.billing_methods', compact('setting', 'billing'));
    }


    public function changeMode(Request $request)
    {
        $dark = $request->dark;
        $shop = User::find(Auth::id());
        $shop->dark = $dark;
        $shop->save();
        return response()->json([
            'status' => true,
            'message' => 'Mode Changed'
        ]);
    }
}
