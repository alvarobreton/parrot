<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];

    public function order()
    {
        return $this->hasMany('App\Model\Orders');
    }
}
