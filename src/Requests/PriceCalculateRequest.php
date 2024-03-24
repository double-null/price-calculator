<?php

namespace App\Requests;

use App\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PriceCalculateRequest - класс запроса расчета цены товара
 */
class PriceCalculateRequest
{
    #[Assert\NotBlank]
    protected $product;

    #[CustomAssert\TaxCode]
    protected $taxNumber;

    protected $couponCode;

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    public function getTaxNumber()
    {
        return $this->taxNumber;
    }

    public function setTaxNumber($taxNumber)
    {
        $this->taxNumber = $taxNumber;
        return $this;
    }

    public function getCouponCode()
    {
        return $this->couponCode;
    }

    public function setCouponCode($couponCode)
    {
        $this->couponCode = $couponCode;
        return $this;
    }
}
