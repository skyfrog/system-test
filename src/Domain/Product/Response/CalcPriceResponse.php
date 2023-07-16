<?php

namespace App\Domain\Product\Response;

class CalcPriceResponse
{

    public function __construct(
        private readonly string $price
    ) {
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }
}