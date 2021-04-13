<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $new_orders = Order::count();
        $designs = 0;
        $requested = 0;
        $approved = Order::where('status', 'approved')->count();
        
        return view('admin.home')->with([
            'new_orders' => $new_orders,
            'designs' => $designs,
            'requested' => $requested,
            'approved' => $approved
        ]);
    }

    public function orders(Request  $request)
    {
        $admin=Auth::user();
        $status=null;
        $orders=Order::query();
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
        return view('admin.order',compact('orders', 'admin'));
    }
}
