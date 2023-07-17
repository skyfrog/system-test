<?php

namespace App\PaymentProcessor;

interface PaymentServiceInterface
{
    public function pay(string $price): PayResultInterface;

    public function method(): string;
}