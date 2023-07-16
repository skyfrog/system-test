<?php

namespace App\DAL\Product\Service;

use App\Domain\Product\Model\CalcPriceModel;
use App\Domain\Product\Service\PriceCalculatorInterface;

class PriceCalculator implements PriceCalculatorInterface
{
    public function calculate(CalcPriceModel $model): string
    {
        return "1.01";
    }
}