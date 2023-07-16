<?php

namespace App\DAL\Product\Service;

use App\Domain\Coupon\Model\ProcessCouponModel;
use App\Domain\Coupon\Service\ProcessCouponInterface;
use App\Domain\Product\Model\CalcPriceModel;
use App\Domain\Product\Service\PriceCalculatorInterface;
use App\Domain\VatNumber\Service\PriceModificatorBasedOnVatNumberInterface;
use App\Exception\ValidationError;
use App\Repository\ProductRepository;

class PriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProcessCouponInterface $processCoupon,
        private readonly PriceModificatorBasedOnVatNumberInterface $priceModificatorBasedOnVatNumber
    ) {
    }

    /**
     * @throws ValidationError
     */
    public function calculate(CalcPriceModel $model): string
    {
        if (!($product = $this->productRepository->find($model->getProductId()))) {
            throw new ValidationError('Product not found');
        }

        $price = $product->getPrice();

        if ($model->getCouponCode()) {
            $price = $this->processCoupon->processCoupon(
                new ProcessCouponModel(
                    $price,
                    $model->getCouponCode()
                )
            );
        }

        if ($model->getTaxNumber()) {
            $price = $this->priceModificatorBasedOnVatNumber->modify($price, $model->getTaxNumber());
        }

        return $price;
    }
}