<?php

namespace App\PaymentProcessor;

use App\Exception\ValidationError;
use Psr\Log\LoggerInterface;

class PaymentFactory
{
    /**
     * @var PaymentServiceInterface[]
     */
    private array $services;

    public function __construct(
        array $services,
        private readonly LoggerInterface $logger
    ) {
        $this->services = $services;
    }

    /**
     * @throws ValidationError
     */
    public function retrievePaymentService(string $method): PaymentServiceInterface
    {
        $services = array_filter(
            ($this->services ?: []),
            function ($s) use ($method) {
                return $s instanceof PaymentServiceInterface && $s->method() === $method;
            }
        );

        $servicesNum = count($services);
        if ($servicesNum === 1) {
            return array_pop($services);
        }

        if ($servicesNum > 1) {
            $this->logger->error("payment factory found {$servicesNum} services for method {$method}");
        }

        throw new ValidationError('Invalid payment method');
    }
}