<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreSaleReportDetail extends Model
{
    protected $fillable = [
        'pre_sale_report_id',
        'resource',
        'supplier_id',
        'cost',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function preSaleReport()
    {
        return $this->belongsTo(PreSaleReport::class, 'pre_sale_report_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
