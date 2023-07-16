<?php

namespace App\Domain\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CalcPriceRequest
{
    #[Assert\NotBlank(message: 'Product ID is required')]
    #[Assert\NotNull(message: 'Product ID is required')]
    #[Assert\Type('integer')]
    public ?int $product = 0;

    #[Assert\Type('string')]
    public ?string $taxNumber = '';

    #[Assert\Type('string')]
    public ?string $couponCode = '';

    #[Assert\NotBlank(message: 'Payment processor is required')]
    #[Assert\NotNull(message: 'Payment processor is required')]
    #[Assert\Type('string')]
    public ?string $paymentProcessor = '';
}