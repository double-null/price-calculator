<?php

namespace App\Controller;

use App\Requests\PriceCalculateRequest;
use App\Service\PriceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * PriceController
 */
class PriceController extends AbstractController
{
    /**
     * Эндпоинт вычисления стоимости товара
     * @param Request $request
     * @param PriceCalculator $priceCalculator
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function calculate(
        Request $request,
        PriceCalculator $priceCalculator,
        ValidatorInterface $validator
    ) :JsonResponse
    {
        $data = $request->toArray();
        $requestParams = (new PriceCalculateRequest())
            ->setProduct($data['product'])
            ->setCouponCode($data['couponCode'])
            ->setTaxNumber($data['taxNumber']);
        $errors = $validator->validate($requestParams);

        if (count($errors) == 0) {
            try {
                return new JsonResponse([
                    'price' => $priceCalculator->calculate(
                        $data['product'],
                        $data['taxNumber'],
                        $data['couponCode'],
                    )
                ]);
            } catch (\Exception $exception) {
                return new JsonResponse(
                    ['errors' => [$exception->getMessage()]],
                    $exception->getStatusCode(),
                );
            }
        } else {
            $validErrors = [];
            foreach ($errors as $error) $validErrors[] = $error->getMessage();
            return new JsonResponse(['errors' => $validErrors], 500);
        }
    }
}
