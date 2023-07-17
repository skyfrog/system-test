<?php

namespace App\DAL\Product\Service;

use App\Domain\Product\Model\ProcessPaymentModel;
use App\Domain\Product\Service\PriceCalculatorInterface;
use App\Domain\Product\Service\ProcessPaymentInterface;
use App\Exception\ValidationError;
use App\PaymentProcessor\PaymentFactory;

class ProcessPayment implements ProcessPaymentInterface
{

    public function __construct(
        private readonly PriceCalculatorInterface $priceCalculator,
        private readonly PaymentFactory $paymentFactory
    ) {
    }

    /**
     * @throws ValidationError
     */
    public function processPayment(ProcessPaymentModel $model): void
    {
        $price = $this->priceCalculator->calculate($model);
        $paymentService = $this->paymentFactory->retrievePaymentService($model->getPaymentProcessor());
        $paymentService->pay($price);
    }
}