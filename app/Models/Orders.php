<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = ['number_order','product_id','total_price'];

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
