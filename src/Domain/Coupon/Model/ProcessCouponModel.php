<?php

namespace App\Domain\Coupon\Model;

class ProcessCouponModel
{
    public function __construct(
        private readonly string $price,
        private readonly string $couponCode
    )
    {
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCouponCode(): string
    {
        return $this->couponCode;
    }
}