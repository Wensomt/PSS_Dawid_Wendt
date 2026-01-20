<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model

{

use HasFactory;


protected $fillable = [

'name',

'description',

'price',

'quantity',

];


protected $casts = [

'price' => 'float',

'created_at' => 'datetime',

'updated_at' => 'datetime',

];


public function getDiscountedPrice($discount = 10): float

{

return $this->price * (1 - $discount / 100);

}

}