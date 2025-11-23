<?php

namespace App\Reports;

use App\Reports\Contracts\ReportStrategy;
use Illuminate\Http\Request;

class ReportContext
{
    /** @var ReportStrategy */
    protected $strategy;

    public function __construct(ReportStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function generate(Request $request)
    {
        return $this->strategy->generate($request);
    }
}
