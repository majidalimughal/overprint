<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCycle extends Model
{
    use HasFactory;


    public function shop()
    {
        return $this->belongsTo(User::class, 'shop_id');
    }

    public function stripeCustomer()
    {
        return StripeCustomer::where('shop_id', $this->shop_id)->where('method', 'stripe')->first();
    }
}
