<?php

namespace App\DAL\VatNumber\Service;

use App\Domain\VatNumber\Service\PriceModificatorBasedOnVatNumberInterface;
use App\Domain\VatNumber\Service\VatNumberValidatorInterface;
use App\Domain\VatNumber\VatNumber;
use App\Exception\ValidationError;

class PriceModificatorBasedOnVatNumber implements PriceModificatorBasedOnVatNumberInterface
{
    public function __construct(
        private readonly VatNumberValidatorInterface $vatNumberValidator
    ) {
    }

    /**
     * @throws ValidationError
     */
    public function modify(string $price, string $vatNumber): string
    {
        if (($countryCode = $this->vatNumberValidator->validate($vatNumber)) === false) {
            throw new ValidationError('VAT number is invalid');
        }

        return bcadd(
            $price,
            bcmul($price, VatNumber::$VAT_NUMBERS[$countryCode]['value'], 2),
            2
        );
    }
}