<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;
use Sashalenz\Privat24BusinessApi\Privat24BusinessApi;
use Sashalenz\Privat24BusinessApi\RequestData\Payments\CreatePaymentRequest;
use Sashalenz\Privat24BusinessApi\ResponseData\Payments\CreatePaymentResponse;
use Sashalenz\Privat24BusinessApi\ResponseData\Payments\GetPaymentResponse;

beforeEach(function () {
    Http::preventStrayRequests();
});

function validCreateRequest(): CreatePaymentRequest
{
    return new CreatePaymentRequest(
        document_number: '123',
        payer_account: 'UA773052990000026001000000001',
        payment_naming: 'ТОВ "Тест"',
        payment_amount: '1250.50',
        payment_destination: 'Оплата за послуги згідно рахунку №42',
        recipient_account: 'UA743052990000026002000000002',
        recipient_nceo: '12345678',
    );
}

it('creates a payment and maps the response', function () {
    Http::fake([
        'acp.privatbank.ua/api/proxy/payment/create' => Http::response([
            'payment_ref' => '1123828417658092296',
            'payment_pack_ref' => 'packed-ref',
        ], 201),
    ]);

    $response = Privat24BusinessApi::payments()
        ->token('test-token')
        ->create(validCreateRequest());

    expect($response)->toBeInstanceOf(CreatePaymentResponse::class)
        ->and($response->payment_ref)->toBe('1123828417658092296')
        ->and($response->payment_pack_ref)->toBe('packed-ref');

    Http::assertSent(function (Request $request) {
        return $request->method() === 'POST'
            && str_ends_with(parse_url($request->url(), PHP_URL_PATH), '/api/proxy/payment/create')
            && $request->header('token') === ['test-token']
            && $request->header('Content-Type') === ['application/json;charset=utf8']
            && $request['payment_amount'] === '1250.50'
            && $request['recipient_account'] === 'UA743052990000026002000000002'
            && $request['payment_destination'] === 'Оплата за послуги згідно рахунку №42';
    });
});

it('rejects a create request without recipient before any HTTP call', function () {
    Http::fake();

    $request = new CreatePaymentRequest(
        document_number: '123',
        payer_account: 'UA773052990000026001000000001',
        payment_naming: 'ТОВ "Тест"',
        payment_amount: '100.00',
        payment_destination: 'Оплата за послуги згідно рахунку №42',
    );

    expect(fn () => Privat24BusinessApi::payments()->token('t')->create($request))
        ->toThrow(Privat24BusinessApiException::class);

    Http::assertNothingSent();
});

it('rejects a malformed amount before any HTTP call', function () {
    Http::fake();

    $request = new CreatePaymentRequest(
        document_number: '123',
        payer_account: 'UA773052990000026001000000001',
        payment_naming: 'ТОВ "Тест"',
        payment_amount: '1 250,50',
        payment_destination: 'Оплата за послуги згідно рахунку №42',
        recipient_account: 'UA743052990000026002000000002',
    );

    expect(fn () => Privat24BusinessApi::payments()->token('t')->create($request))
        ->toThrow(Privat24BusinessApiException::class);

    Http::assertNothingSent();
});

it('fetches payment state by ref', function () {
    Http::fake([
        'acp.privatbank.ua/api/proxy/payment/get*' => Http::response([
            'payment_ref' => '1123828417658092296',
            'payment_status' => 'new',
            'document_number' => 'test',
            'payment_amount' => '1.02',
            'level_sign' => ['1_sign_level' => false, '2_sign_level' => false],
            'fields_for_sign' => ['version' => 'v3.5.0', 'fields' => ['payment_ref']],
        ]),
    ]);

    $response = Privat24BusinessApi::payments()
        ->token('test-token')
        ->get('1123828417658092296');

    expect($response)->toBeInstanceOf(GetPaymentResponse::class)
        ->and($response->payment_status)->toBe('new')
        ->and($response->payment_amount)->toBe('1.02')
        ->and($response->level_sign)->toBe(['1_sign_level' => false, '2_sign_level' => false]);

    Http::assertSent(function (Request $request) {
        return $request->method() === 'GET'
            && str_contains($request->url(), '/api/proxy/payment/get')
            && str_contains($request->url(), 'ref=1123828417658092296');
    });
});

it('deletes an unsigned payment via POST with ref in query', function () {
    Http::fake([
        'acp.privatbank.ua/api/proxy/payment/delete*' => Http::response(null, 204),
    ]);

    $result = Privat24BusinessApi::payments()
        ->token('test-token')
        ->delete('1123828417658092296');

    expect($result)->toBeTrue();

    Http::assertSent(function (Request $request) {
        return $request->method() === 'POST'
            && str_contains($request->url(), '/api/proxy/payment/delete?ref=1123828417658092296')
            // The API expects a truly empty body — not the JSON string '[]'.
            && $request->body() === '';
    });
});

it('throws on API error response', function () {
    Http::fake([
        'acp.privatbank.ua/api/proxy/payment/create' => Http::response(['code' => 'error'], 400),
    ]);

    expect(fn () => Privat24BusinessApi::payments()->token('t')->create(validCreateRequest()))
        ->toThrow(Privat24BusinessApiException::class);
});
