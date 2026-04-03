<?php

namespace App\Models;

use App\Models\Category;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image'
    ];

    protected $casts = [
        'price'=> 'decimal:2'
    ];



    public function category(){
        return $this->belongsTo(Category::class);
    }
}
