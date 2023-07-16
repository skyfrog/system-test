<?php

namespace App\Controller;

use App\Domain\Product\Model\CalcPriceModel;
use App\Domain\Product\Model\ProcessPaymentModel;
use App\Domain\Product\Request\CalcPriceRequest;
use App\Domain\Product\Request\PaymentRequest;
use App\Domain\Product\Response\CalcPriceResponse;
use App\Domain\Product\Service\PriceCalculatorInterface;
use App\Domain\Product\Service\ProcessPaymentInterface;
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
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly PriceCalculatorInterface $priceCalculator,
        private readonly ProcessPaymentInterface $processPayment
    ) {
    }

    /**
     * @throws ValidationError
     */
    #[Route('/calc-price', name: 'product_calc_price', methods: ['post'])]
    public function calcPrice(Request $request): Response
    {
        /**
         * @var CalcPriceRequest $requestData
         */
        $requestData = $this->validateRequest(
            $request,
            CalcPriceType::class,
            CalcPriceRequest::class
        );
        return $this->json(
            new CalcPriceResponse(
                $this->priceCalculator->calculate(
                    new CalcPriceModel(
                        $requestData->product,
                        $requestData->taxNumber,
                        $requestData->couponCode,
                        $requestData->paymentProcessor
                    )
                )
            )
        );
    }

    /**
     * @throws ValidationError
     */
    private function validateRequest(
        Request $request,
        string $formType,
        string $requestType
    ): mixed {
        $data = $request->getContent();
        $form = $this->createForm($formType);
        $form->submit(json_decode($data, true));
        $requestExample = new $requestType();

        if (!$form->isValid()) {
            throw new ValidationError($form->getErrors(true)->current()->getMessage(), $requestExample);
        }

        try {
            return $this->serializer->deserialize($data, $requestType, 'json');
        } catch (UnexpectedValueException) {
            throw new ValidationError('badly formatted JSON', $requestExample);
        }
    }

    /**
     * @throws ValidationError
     */
    #[Route('/payment', name: 'product_payment', methods: ['post'])]
    public function payment(Request $request): Response
    {
        /**
         * @var PaymentRequest $requestData
         */
        $requestData = $this->validateRequest(
            $request,
            PaymentType::class,
            PaymentRequest::class
        );
        $this->processPayment->processPayment(
            new ProcessPaymentModel(
                $requestData->product,
                $requestData->taxNumber,
                $requestData->couponCode,
                $requestData->paymentProcessor
            )
        );
        return new Response('', Response::HTTP_OK);
    }
}
