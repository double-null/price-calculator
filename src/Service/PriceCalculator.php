<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class PriceCalculator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function calculate($product, $taxNumber, $couponCode)
    {
        $countryTag = substr($taxNumber, 0, 2);
        $product = $this->em->find(Product::class, $product);
        $country = $this->em->getRepository(Country::class)
            ->findOneByTag($countryTag);
        $price = $product->getPrice();

        // При наличии купона, понижаем цену товара
        if (!empty($couponCode)) {
            $coupon = $this->em->getRepository(Coupon::class)
                ->findOneByCode($couponCode);
            if (!empty($coupon)) {
                $price = $price - ($price * $coupon->getDiscount() / 100);
            }
        }

        // Добавляем налог относительно страны
        $price = $price + ($price * $country->getPercent() / 100);

        return $price;
    }
}