<?php

namespace App\Domain\Coupon\Service;

use App\Domain\Coupon\Model\ProcessCouponModel;

interface ProcessCouponInterface
{
    public function processCoupon(ProcessCouponModel $model): string;
}