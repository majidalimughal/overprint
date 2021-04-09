<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function billingMethods()
    {
        $shop=Auth::user();
        $setting=Setting::where('shop',$shop->name)->first();
        if ($setting===null)
        {
            $setting=new Setting();
            $setting->shop=$shop->name;
            $setting->save();
        }
        return view('admin.billing_methods',compact('setting'));
    }
}
