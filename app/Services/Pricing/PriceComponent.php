<?php

namespace App\Services\Pricing;

interface PriceComponent
{
    /**
     * Calcula el precio unitario a partir del costo total y la cantidad.
     */
    public function calculate(float $baseCost, int $quantity): float;
}
