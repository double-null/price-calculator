<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints as Assert;

class ProductPurchaseRequest extends PriceCalculateRequest
{
    #[Assert\NotBlank]
    protected $paymentProcessor;

    public function getPaymentProcessor()
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor($paymentProcessor) : static
    {
        $this->paymentProcessor = $paymentProcessor;
        return $this;
    }
}
