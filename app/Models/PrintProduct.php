<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintProduct extends Model
{
    use HasFactory;

    public function has_StoreProduct()
    {
        return $this->hasMany(Product::class, 'print_product_id');
    }

    public function hasSale()
    {
        return $this->hasMany(ProductSale::class, 'print_product_id');
    }

    public function getImagesAttribute($images)
    {
        if ($images) {
            return json_decode($images);
        } else return [];
    }
    // public function getMockupsAttribute($images)
    // {
    //     if ($images) {
    //         return json_decode($images);
    //     } else return [];
    // }

    // public function getArtworksAttribute($images)
    // {
    //     if ($images) {
    //         return json_decode($images);
    //     } else return [];
    // }
}
