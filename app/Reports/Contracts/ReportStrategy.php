<?php
namespace App\Reports\Contracts;

use Illuminate\Http\Request;

interface ReportStrategy
{
    public function generate(Request $request);
}