<?php

namespace App\DataFixtures;

use App\Domain\Coupon\CouponType;
use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            'iPhone' => 100,
            'Наушники' => 20,
            'Чехол' => 10
        ];

        foreach ($products as $name => $price) {
            $product = new Product();
            $product->setPrice("{$price}");
            $product->setName($name);
            $manager->persist($product);
        }

        $coupon1 = new Coupon();
        $coupon1->setCode('summer_sale_absolute');
        $coupon1->setType(CouponType::ABSOLUTE);
        $coupon1->setDiscount('7.01');
        $manager->persist($coupon1);

        $coupon2 = new Coupon();
        $coupon2->setCode('summer_sale_percentage');
        $coupon2->setType(CouponType::PERCENTAGE);
        $coupon2->setDiscount('6.00');
        $manager->persist($coupon2);

        $manager->flush();
    }
}
