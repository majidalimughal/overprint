<?php

namespace App\Models;

use App\Models\FulfillmentLineItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderFulfillment extends Model
{
    use HasFactory;

    public function line_items(){
        return $this->hasMany(FulfillmentLineItem::class,'order_fulfillment_id');
    }
}
