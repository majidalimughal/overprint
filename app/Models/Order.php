<?php

namespace App\Models;

use App\Models\OrderFulfillment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public function has_shop()
    {
        return $this->belongsTo(User::class,'shop','name');
    }
    public function has_lineItems()
    {
        return $this->hasMany(LineItem::class,'order_id');
    }
    public function getShippingAddressAttribute($address)
    {
        if($address)
        {
            return json_decode($address,true);
        }else
        {
            return null;
        }
    }
    public function getBillingAddressAttribute($address)
    {
        if($address)
        {
            return json_decode($address,true);
        }else
        {
            return null;
        }
    }
    public function getPaymentAttribute($val)
    {
        if ($val!==null)
        {
            return json_decode($val,true);
        }else
        {
            return null;
        }
    }

    public function getStatus($order){
        $quanity = $order->has_lineItems->sum('quantity');
        $fulfillable_quanity = $order->has_lineItems->sum('fulfillable_quantity');
        if($fulfillable_quanity == 0){
            return 'fulfilled';
        }
        else if($fulfillable_quanity == $quanity || $fulfillable_quanity < $quanity){
            return 'unfulfilled';
        }
    }

    public function fulfillments() {
        return $this->hasMany(OrderFulfillment::class);
    }
}
