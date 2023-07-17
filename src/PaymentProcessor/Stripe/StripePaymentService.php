<?php

namespace App\PaymentProcessor\Stripe;

use App\Exception\ValidationError;
use App\PaymentProcessor\External\StripePaymentProcessor;
use App\PaymentProcessor\PaymentMethod;
use App\PaymentProcessor\PaymentServiceInterface;
use App\PaymentProcessor\PayResultInterface;
use Psr\Log\LoggerInterface;

class StripePaymentService implements PaymentServiceInterface
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
            $processor = new StripePaymentProcessor();
            if (!$processor->processPayment($price)) {
                throw new ValidationError('Could not use Stripe for payment with current purchase');
            }
        } catch (ValidationError $ve) {
            throw $ve;
        } catch (\Throwable $e) {
            $this->logger->error("stripe processor pay failure: {$e->getMessage()}", ['exception' => $e]);
            throw new ValidationError('Could not proceed to payment');
        }

        return new StripePayResult();
    }

    public function method(): string
    {
        return PaymentMethod::STRIPE;
    }
}