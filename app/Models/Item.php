<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'price'
    ];


    public function product(){
        return $this->belongsTo('App/Models/Product');
    }

    public function itemable()
    {
        return $this->morphTo();
    }
}
