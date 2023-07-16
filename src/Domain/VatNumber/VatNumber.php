<?php

namespace App\Domain\VatNumber;

class VatNumber
{
    public static $VAT_NUMBERS = [
        'DE' => [
            'regex' => "/^DE[0-9]{9}$/",
            'value' => '0.19'
        ],
        'IT' => [
            'regex' => "/^IT[0-9]{11}$/",
            'value' => '0.22'
        ],
        'FR' => [
            'regex' => "/^FR[A-Z]{2}[0-9]{9}$/",
            'value' => '0.20'
        ],
        'GR' => [
            'regex' => "/^GR[0-9]{9}$/",
            'value' => '0.24'
        ]
    ];
}