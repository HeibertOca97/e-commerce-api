<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'total',
        'address',
        'status'
    ];

    public function user(){
        return $this->belongsTo('App/Models/User');
    }

    public function items(){
        return $this->morphMany('App/Models/Items', 'itemable');
    }
}
