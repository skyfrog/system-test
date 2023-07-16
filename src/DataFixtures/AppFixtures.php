<?php

namespace App\DataFixtures;

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

        $coupon = new Coupon();
        $coupon->setCode('summer_sale');
        $manager->persist($coupon);

        $manager->flush();
    }
}
