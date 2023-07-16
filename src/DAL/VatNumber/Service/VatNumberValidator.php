<?php

namespace App\DAL\VatNumber\Service;

use App\Domain\VatNumber\Service\VatNumberValidatorInterface;
use App\Domain\VatNumber\VatNumber;

class VatNumberValidator implements VatNumberValidatorInterface
{
    public function validate(string $number): bool|string
    {
        foreach (VatNumber::$VAT_NUMBERS as $countryCode => $info) {
            if (preg_match($info['regex'], $number)) {
                return $countryCode;
            }
        }

        return false;
    }
}