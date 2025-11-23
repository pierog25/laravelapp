<?php

namespace App\Factories;

use App\Order;
use App\OrderDetail;

class OrderFactory
{
    // llamándolo Factory Method
    public static function createFromArray(array $data): Order
    {
        // 1. Crear la orden
        $order = Order::create([
            'client_id'         => $data['client_id'],
            'delivery_date'     => $data['delivery_date'],
            'delivery_location' => $data['delivery_location'],
            'order_status'      => 'Por Cotizar',
            'issue_date'        => $data['issue_date'],
            'user_id'           => $data['user_id'],
            'status'            => true
        ]);

        // 2. Crear los detalles
        foreach ($data['order_details'] as $detail) {
            $order->details()->create([
                'product_id'   => $detail['product_id'],
                'description'  => $detail['description'] ?? null,
                'quantity'     => $detail['quantity'],
                'status'       => true,
            ]);
        }

        return $order;
    }
}
