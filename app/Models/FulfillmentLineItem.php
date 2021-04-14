<?php

namespace App\Models;

use App\Models\LineItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FulfillmentLineItem extends Model
{
    use HasFactory;

    public function line_item() {
        return $this->belongsTo(LineItem::class, 'order_line_item_id');
    }
}

