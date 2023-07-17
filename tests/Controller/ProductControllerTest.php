<?php

namespace App\Tests\Controller;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends TestCase
{
    public function testCalcPrice(): void
    {
        $endpoint = '/api/product/calc-price';

        $response = $this->performRequest($endpoint, [
            'product' => null,
            'paymentProcessor' => 'paypal'
        ]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $this->decodeResponseBody($response));

        $response = $this->performRequest($endpoint, [
            'product' => 1,
            'paymentProcessor' => 'paypal'
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = $this->decodeResponseBody($response);
        $this->assertArrayHasKey('price', $responseData);
        $this->assertEquals('100.00', $responseData['price']);

        $response = $this->performRequest($endpoint, [
            'product' => 1,
            'paymentProcessor' => 'paypal',
            'couponCode' => md5(time())
        ]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $this->decodeResponseBody($response));

        $response = $this->performRequest($endpoint, [
            'product' => 1,
            'paymentProcessor' => 'paypal',
            'couponCode' => 'summer_sale_absolute'
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = $this->decodeResponseBody($response);
        $this->assertArrayHasKey('price', $responseData);
        $this->assertEquals('92.99', $responseData['price']);

        $response = $this->performRequest($endpoint, [
            'product' => 1,
            'paymentProcessor' => 'paypal',
            'couponCode' => 'summer_sale_percentage'
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = $this->decodeResponseBody($response);
        $this->assertArrayHasKey('price', $responseData);
        $this->assertEquals('94.00', $responseData['price']);

        $response = $this->performRequest($endpoint, [
            'product' => 1,
            'paymentProcessor' => 'paypal',
            'couponCode' => 'summer_sale_percentage',
            'taxNumber' => md5(time())
        ]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = $this->decodeResponseBody($response);
        $this->assertArrayHasKey('error', $responseData);

        $response = $this->performRequest($endpoint, [
            'product' => 1,
            'paymentProcessor' => 'paypal',
            'couponCode' => 'summer_sale_percentage',
            'taxNumber' => 'GR123456789'
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = $this->decodeResponseBody($response);
        $this->assertArrayHasKey('price', $responseData);
        $this->assertEquals('116.56', $responseData['price']);
    }

    public function testPayment(): void
    {
        $endpoint = '/api/product/payment';

        $response = $this->performRequest($endpoint, [
            'product' => 1,
            'paymentProcessor' => md5(time())
        ]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $this->decodeResponseBody($response));

        $response = $this->performRequest($endpoint, [
            'product' => 1,
            'paymentProcessor' => 'paypal',
            'taxNumber' => 'DE123456789'
        ]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $this->decodeResponseBody($response));

        $response = $this->performRequest($endpoint, [
            'product' => 1,
            'paymentProcessor' => 'stripe',
            'taxNumber' => 'DE123456789'
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $response = $this->performRequest($endpoint, [
            'product' => 3,
            'paymentProcessor' => 'stripe',
            'couponCode' => 'summer_sale_percentage'
        ]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $this->decodeResponseBody($response));

        $response = $this->performRequest($endpoint, [
            'product' => 3,
            'paymentProcessor' => 'paypal',
            'couponCode' => 'summer_sale_percentage'
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    private function performRequest(string $endpoint, array $data): ResponseInterface
    {
        return (new Client([
            'base_uri' => 'http://caddy',
            'http_errors' => false
        ]))->post($endpoint, [
            'body' => json_encode($data)
        ]);
    }

    private function decodeResponseBody(ResponseInterface $response) {
        return json_decode((string)$response->getBody(), true);
    }
}
