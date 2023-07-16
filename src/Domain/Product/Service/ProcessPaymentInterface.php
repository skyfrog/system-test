<?php

namespace App\Domain\Product\Service;

use App\Domain\Product\Model\ProcessPaymentModel;

interface ProcessPaymentInterface
{
    public function processPayment(ProcessPaymentModel $model): void;
}