<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'image',
        'categories',
        'size',
        'color',
        'price'
    ];

    public function item(){
        return $this->hasOne('App/Models/Item');
    }

}
