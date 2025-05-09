<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;

class Product extends Model
{   
    // protected $table = "product";
    protected $fillable = [
        "product_name",
        "stock",
        "price",
        "description",
        "category",
        "image",
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
