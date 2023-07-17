<?php

namespace App\PaymentProcessor\PayPal;

use App\Exception\ValidationError;
use App\PaymentProcessor\External\PaypalPaymentProcessor;
use App\PaymentProcessor\PaymentMethod;
use App\PaymentProcessor\PaymentServiceInterface;
use App\PaymentProcessor\PayResultInterface;
use Psr\Log\LoggerInterface;

class PayPalPaymentService implements PaymentServiceInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws ValidationError
     */
    public function pay(string $price): PayResultInterface
    {
        try {
            $processor = new PaypalPaymentProcessor();
            $processor->pay($price);
        } catch (\Exception $e) {
            $this->logger->error("paypal processor pay failure: {$e->getMessage()}", ['exception' => $e]);
            throw new ValidationError($e->getMessage());
        }

        return new PayPalPayResult();
    }

    public function method(): string
    {
        return PaymentMethod::PAYPAL;
    }
}