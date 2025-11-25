<?php

namespace App\Services\Pricing;

class BasePrice implements PriceComponent
{
    public function calculate(float $baseCost, int $quantity): float
    {
        if ($quantity <= 0) {
            return 0;
        }

        return round($baseCost / $quantity, 2);
    }
}
