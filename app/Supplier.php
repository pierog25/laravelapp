<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'ruc',
        'address',
        'email',
        'first_phone',
        'second_phone',
        'third_phone',
        'type',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function preSaleReportDetails()
    {
        return $this->hasMany(PreSaleReportDetail::class);
    }
}
