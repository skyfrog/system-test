<?php

namespace App\Domain\Product\Model;

class CalcPriceModel
{

    public function __construct(
        private readonly ?int $productId,
        private readonly ?string $taxNumber,
        private readonly ?string $couponCode,
        private readonly ?string $paymentProcessor
    ) {
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @return string|null
     */
    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    /**
     * @return string|null
     */
    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    /**
     * @return string|null
     */
    public function getPaymentProcessor(): ?string
    {
        return $this->paymentProcessor;
    }
}