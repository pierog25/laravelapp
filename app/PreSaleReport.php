<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreSaleReport extends Model
{
    protected $fillable = [
        'order_detail_id',
        'unit_price',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function details()
    {
        return $this->hasMany(PreSaleReportDetail::class, 'pre_sale_report_id');
    }
    
    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }
}
