<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function variantsCount()
    {
        $variants=json_decode($this->variants);
        if(count($variants))
        {
            return collect($variants)->pluck('title')->toArray();
        }else
        {
            return [];
        }
    }

    public function has_print_product()
    {
        return $this->belongsTo(PrintProduct::class,'print_product_id');
    }
}
