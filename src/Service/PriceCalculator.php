<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PriceCalculator - сервис расчета цены
 */
class PriceCalculator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Расчет стоимости товара
     * @param $product - номер продукта
     * @param $taxNumber - налоговый номер
     * @param $couponCode - код купона
     * @return float|int|null
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function calculate($product, $taxNumber, $couponCode)
    {
        $countryTag = substr($taxNumber, 0, 2);
        $product = $this->em->find(Product::class, $product);
        $country = $this->em->getRepository(Country::class)
            ->findOneByTag($countryTag);

        if (empty($product)) {
            throw new NotFoundHttpException("Товар не найден");
        }

        if (empty($country)) {
            throw new NotFoundHttpException("Страна не найдена");
        }

        $price = $product->getPrice();
        // При наличии купона, понижаем цену товара
        if (!empty($couponCode)) {
            $coupon = $this->em->getRepository(Coupon::class)
                ->findOneByCode($couponCode);
            if (!empty($coupon)) {
                if ($coupon->getType() == 1) {
                    // скидка - процент
                    $price = $price - ($price * $coupon->getDiscount() / 100);
                } else {
                    // скидка - фиксированная
                    $price -= $coupon->getDiscount();
                }
            }
        }

        // Добавляем налог относительно страны
        $price = $price + ($price * $country->getPercent() / 100);

        return $price;
    }
}
