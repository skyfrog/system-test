<?php

namespace App\Domain\VatNumber\Service;

interface PriceModificatorBasedOnVatNumberInterface
{
    public function modify(string $price, string $vatNumber): string;
}