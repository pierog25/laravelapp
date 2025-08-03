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
        'status',
        'cost_final',
        'cost_additional',
        'date_delivery',
        'date_receipt',
        'comments_cost',
        'comment_quality'
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
