<?php

namespace App\Domain\Product\Service;

use App\Domain\Product\Model\CalcPriceModel;

interface PriceCalculatorInterface
{
    public function calculate(CalcPriceModel $model): string;
}