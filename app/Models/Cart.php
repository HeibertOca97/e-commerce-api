<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total'
    ];

    public function items(){
        return $this->morphMany('App/Models/Items', 'itemable');
    }

    public function user(){
        return $this->belongsTo('App/Models/User');
    }

}
