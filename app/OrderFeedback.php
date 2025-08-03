<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderFeedback extends Model
{
    protected $table = 'order_feedback';

    protected $fillable = [
        'order_id',
        'quality_score',
        'compliance_score',
        'attention_score',
    ];

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
