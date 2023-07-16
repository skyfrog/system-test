<?php

namespace App\Domain\VatNumber\Service;

interface VatNumberValidatorInterface
{
    /**
     * @param string $number
     * @return bool|string false if invalid, country code if valid
     */
    public function validate(string $number): bool|string;
}