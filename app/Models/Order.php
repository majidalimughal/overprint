<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
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
}
