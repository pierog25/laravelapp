<?php

namespace App\Reports;

use App\Order;
use App\Reports\Contracts\ReportStrategy;
use Illuminate\Http\Request;

class ClientReport implements ReportStrategy
{
    public function generate(Request $request)
    {
        $documentNumber = $request->input('document_number');
        $fromDate = $request->input('desde');
        $toDate   = $request->input('hasta');

        $query = $this->baseQuery();

        if (!empty($documentNumber)) {
            $query->whereHas('client', function ($q) use ($documentNumber) {
                $q->where('document_number', $documentNumber);
            });
        }

        // opcional: también filtrar por fechas si vienen
        if ($fromDate && $toDate) {
            $query->whereBetween('issue_date', [$fromDate, $toDate]);
        }

        return $query
            ->orderBy('issue_date', 'desc')
            ->get();
    }

    protected function baseQuery()
    {
        return Order::where('status', true)
            ->with([
                'client',
                'user',
                'details' => function ($query) {
                    $query->where('status', true)
                        ->with(['preSaleReport' => function ($q) {
                            $q->where('status', true)
                                ->with(['details' => function ($d) {
                                    $d->where('status', true)
                                      ->with(['supplier']);
                                }]);
                        }]);
                }
            ]);
    }
}
