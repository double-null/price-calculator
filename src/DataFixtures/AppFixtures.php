<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Добавляем товары
        $products = [
            ['name' => 'Iphone', 'price' => 100],
            ['name' => 'Наушники', 'price' => 20],
            ['name' => 'Чехол', 'price' => 10],
        ];
        foreach ($products as $product) {
            $productEntity = (new Product())
                ->setName($product['name'])
                ->setPrice($product['price']);
            $manager->persist($productEntity);
        }

        // Генерируем скидочные купоны
        $discounts = [10, 20, 50, 100];
        for ($i = 1; $i < 10; $i++) {
            $type = rand(1, 2);
            $discount = $discounts[array_rand($discounts)];
            $firstChar = ($type == 1) ? 'P' : 'D';
            $code = $firstChar.$discount.'-'.strtoupper(substr(md5(rand()), 0, 10));

            $coupon = (new Coupon())
                ->setCode($code)
                ->setDiscount($discount)
                ->setType($type);
            $manager->persist($coupon);
        }

        // Добавляем страны
        $countries = [
            ['name' => 'Германия', 'tag' => 'DE', 'percent' => 19, 'format' => 'DEXXXXXXXXX'],
            ['name' => 'Италия', 'tag' => 'IT', 'percent' => 22, 'format' => 'ITXXXXXXXXXXX'],
            ['name' => 'Франция', 'tag' => 'FR', 'percent' => 20, 'format' => 'FRYYXXXXXXXXX'],
            ['name' => 'Греция', 'tag' => 'GR', 'percent' => 24, 'format' => 'GRXXXXXXXXX'],
        ];
        foreach ($countries as $country) {
            $countryEntity = (new Country())
                ->setName($country['name'])
                ->setTag($country['tag'])
                ->setPercent($country['percent'])
                ->setTaxFormat($country['format']);
            $manager->persist($countryEntity);
        }

        $manager->flush();
    }
}
