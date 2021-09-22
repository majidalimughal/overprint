<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    use HasFactory;

    protected $fillables = [
        'order_id',
        'product_id',
        'print_product_id',
        'sale'
    ];
}
