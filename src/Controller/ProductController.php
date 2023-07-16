<?php

namespace App\Controller;

use App\Domain\Product\Request\CalcPriceRequest;
use App\Domain\Product\Request\PaymentRequest;
use App\Exception\ValidationError;
use App\Validation\Form\Type\Product\CalcPriceType;
use App\Validation\Form\Type\Product\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/product', name: 'api_')]
class ProductController extends AbstractController
{
    /**
     * @throws ValidationError
     */
    #[Route('/calc-price', name: 'product_calc_price', methods: ['post'])]
    public function calcPrice(Request $request, SerializerInterface $serializer): Response
    {
        $requestData = $this->validateRequest(
            $request,
            CalcPriceType::class,
            CalcPriceRequest::class,
            $serializer
        );
        return $this->json($requestData);
    }

    /**
     * @throws ValidationError
     */
    #[Route('/payment', name: 'product_payment', methods: ['post'])]
    public function payment(Request $request, SerializerInterface $serializer): Response
    {
        $requestData = $this->validateRequest(
            $request,
            PaymentType::class,
            PaymentRequest::class,
            $serializer
        );
        return $this->json($requestData);
    }

    /**
     * @throws ValidationError
     */
    private function validateRequest(
        Request $request,
        string $formType,
        string $requestType,
        SerializerInterface $serializer
    ): mixed {
        $data = $request->getContent();
        $form = $this->createForm($formType);
        $form->submit(json_decode($data, true));
        $requestExample = new $requestType();

        if (!$form->isValid()) {
            throw new ValidationError($form->getErrors(true)->current()->getMessage(), $requestExample);
        }

        try {
            return $serializer->deserialize($data, $requestType, 'json');
        } catch (UnexpectedValueException) {
            throw new ValidationError('badly formatted JSON', $requestExample);
        }
    }
}
