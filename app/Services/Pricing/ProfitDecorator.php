<?php

namespace App\Services\Pricing;

class ProfitDecorator extends PriceDecorator
{
    protected $profitRate;
    
    public function __construct(PriceComponent $component, $profitRate)
    {
        parent::__construct($component);
        $this->profitRate = $profitRate;
    }

    public function calculate(float $baseCost, int $quantity): float
    {
        $price = parent::calculate($baseCost, $quantity);
        $price = $price * (1 + $this->profitRate);

        return round($price, 2);
    }
}
