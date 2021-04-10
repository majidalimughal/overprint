<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function variantsCount()
    {
        $variants = $this->variants;
        if (count($variants)) {
            return collect($variants)->pluck('title')->toArray();
        } else {
            return [];
        }
    }

    public function getVariantsAttribute($variants)
    {
        if ($variants !== null) {
            return json_decode($variants);
        } else return [];
    }
    public function getImagesAttribute($images)
    {
        if ($images !== null) {
            return json_decode($images);
        } else return [];
    }

    public function getOptionsAttribute($options)
    {
        if ($options !== null) {
            return json_decode($options);
        } else return [];
    }

    public function has_print_product()
    {
        return $this->belongsTo(PrintProduct::class, 'print_product_id');
    }
}
