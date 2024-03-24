<?php

namespace App\Controller;

use App\Requests\ProductPurchaseRequest;
use App\Service\PriceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class ProductController extends AbstractController
{
    #[Route('/purchase', name: 'product_purchase', methods: ['POST'])]
    public function purchase(
        Request $request,
        PriceCalculator $priceCalculator,
        ValidatorInterface $validator
    ) : JsonResponse
    {
        $data = $request->toArray();
        $requestParams = (new ProductPurchaseRequest())
            ->setProduct($data['product'])
            ->setCouponCode($data['couponCode'])
            ->setTaxNumber($data['taxNumber'])
            ->setPaymentProcessor($data['paymentProcessor']);
        $errors = $validator->validate($requestParams);

        if (count($errors) > 0) {
            $validErrors = [];
            foreach ($errors as $error) $validErrors[] = $error->getMessage();
            return new JsonResponse(['errors' => $validErrors], 500);
        }

        $price = $priceCalculator->calculate(
            $data['product'],
            $data['taxNumber'],
            $data['couponCode'],
        );

        try {
            $res = match ($data['paymentProcessor']) {
                'paypal' => (new PaypalPaymentProcessor())->pay($price),

                // Можно добавить другие платежки
            };
        } catch (\Exception $exception) {
            return new JsonResponse(['errors' => [$exception->getMessage()]], 500);
        }

        return new JsonResponse();
    }
}
