<?php

namespace App\Services\Pricing;

abstract class PriceDecorator implements PriceComponent
{
    protected $component;

    public function __construct(PriceComponent $component)
    {
        $this->component = $component;
    }

    public function calculate(float $baseCost, int $quantity): float
    {
        return $this->component->calculate($baseCost, $quantity);
    }
}
