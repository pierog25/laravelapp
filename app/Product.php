<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'abbreviation', 'status'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
