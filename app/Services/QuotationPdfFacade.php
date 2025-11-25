<?php

namespace App\Services;

use App\Order;
use Carbon\Carbon;
use PDF; // ya lo usas en el controlador

class QuotationPdfFacade
{
    /**
     * Genera el PDF de la cotización y devuelve la respuesta stream().
     * Puedes hacer otro método download() si quieres descargarlo.
     */
    public function stream(int $id)
    {
        // --- Esta parte es prácticamente tu método pdf() tal cual ---

        $order = Order::where('id', $id)
            ->where('status', true)
            ->with([
                'client',
                'user',
                'details' => function ($query) {
                    $query->where('status', true)
                        ->with(['preSaleReport' => function ($q) {
                            $q->where('status', true);
                        }]);
                }
            ])
            ->firstOrFail();

        // Armar los ítems
        $items = [];
        $total = 0;

        foreach ($order->details as $detail) {
            $unitPrice = $detail->preSaleReport->unit_price ?? 0;
            $subtotal = $detail->quantity * $unitPrice;
            $items[] = [
                'cantidad'        => $detail->quantity,
                'unidad'          => 'UND',
                'descripcion'     => $detail->description,
                'precio_unitario' => $unitPrice,
                'precio_total'    => $subtotal
            ];
            $total += $subtotal;
        }

        // Armar la data para la vista
        $data = [
            'numero'  => str_pad($order->id, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($order->issue_date ?? now())),
            'cliente' => $order->client->first_name . ' ' . $order->client->last_name,
            'fecha'   => Carbon::parse($order->issue_date)->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
            'atencion'=> $order->client->first_name,
            'total'   => $total,
            'items'   => $items
        ];

        // Generar PDF usando la vista que ya tienes
        $pdf = PDF::loadView('pdf.cotizacion', ['cotizacion' => $data]);

        // devolvemos el stream, igual que antes
        return $pdf->stream('cotizacion.pdf');
    }

    /**
     * Si quieres también la opción de descarga:
     */
    public function download(int $id)
    {
        $order = Order::where('id', $id)
            ->where('status', true)
            ->with([
                'client',
                'user',
                'details' => function ($query) {
                    $query->where('status', true)
                        ->with(['preSaleReport' => function ($q) {
                            $q->where('status', true);
                        }]);
                }
            ])
            ->firstOrFail();

        $items = [];
        $total = 0;

        foreach ($order->details as $detail) {
            $unitPrice = $detail->preSaleReport->unit_price ?? 0;
            $subtotal = $detail->quantity * $unitPrice;
            $items[] = [
                'cantidad'        => $detail->quantity,
                'unidad'          => 'UND',
                'descripcion'     => $detail->description,
                'precio_unitario' => $unitPrice,
                'precio_total'    => $subtotal
            ];
            $total += $subtotal;
        }

        $data = [
            'numero'  => str_pad($order->id, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($order->issue_date ?? now())),
            'cliente' => $order->client->first_name . ' ' . $order->client->last_name,
            'fecha'   => Carbon::parse($order->issue_date)->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
            'atencion'=> $order->client->first_name,
            'total'   => $total,
            'items'   => $items
        ];

        $pdf = PDF::loadView('pdf.cotizacion', ['cotizacion' => $data]);

        return $pdf->download('cotizacion.pdf');
    }
}
