<?php

namespace App\DAL\Coupon\Service;

use App\Domain\Coupon\CouponType;
use App\Domain\Coupon\Model\ProcessCouponModel;
use App\Domain\Coupon\Service\ProcessCouponInterface;
use App\Exception\ValidationError;
use App\Repository\CouponRepository;

class ProcessCoupon implements ProcessCouponInterface
{
    public function __construct(
        private readonly CouponRepository $couponRepository
    ) {
    }

    /**
     * @throws ValidationError
     */
    public function processCoupon(ProcessCouponModel $model): string
    {
        if (!($coupon = $this->couponRepository->findOneBy(['code' => $model->getCouponCode()]))) {
            throw new ValidationError('Coupon not found');
        }

        $price = $model->getPrice();

        switch ($coupon->getType()) {
            case CouponType::ABSOLUTE:
                $price = bcsub($price, $coupon->getDiscount(), 2);
                break;
            case CouponType::PERCENTAGE:
                $price = bcmul(
                    bcdiv($price, '100.00', 2),
                    bcsub('100.00', $coupon->getDiscount(), 2),
                    2
                );
                break;
            default:
                throw new ValidationError('Invalid coupon type');
        }

        return $price;
    }
}