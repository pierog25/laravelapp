<?php

namespace App\Reports;

use App\Order;
use App\Reports\Contracts\ReportStrategy;
use Illuminate\Http\Request;

class DateRangeReport implements ReportStrategy
{
    public function generate(Request $request)
    {
        $fromDate = $request->input('desde', now()->toDateString());
        $toDate   = $request->input('hasta', now()->toDateString());

        $query = $this->baseQuery();

        if ($fromDate && $toDate) {
            $query->whereBetween('issue_date', [$fromDate, $toDate]);
        }

        return $query
            ->orderBy('issue_date', 'desc')
            ->get();
    }

    /**
     * Arma el query base con los with() y filtros de status=true
     */
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
