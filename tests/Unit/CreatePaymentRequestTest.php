<?php

use Sashalenz\Privat24BusinessApi\RequestData\Payments\CreatePaymentRequest;

it('omits null optional fields from the payload', function () {
    $request = new CreatePaymentRequest(
        document_number: '1',
        payer_account: 'UA1',
        payment_naming: 'Test',
        payment_amount: '10.00',
        payment_destination: 'Оплата за послуги',
        recipient_account: 'UA2',
    );

    // BaseModel::getParams() array_filters the payload the same way.
    $payload = array_filter($request->toArray());

    expect($payload)->not->toHaveKeys(['recipient_card', 'payment_ccy', 'payment_date'])
        ->and($payload['recipient_account'])->toBe('UA2');
});

it('keeps amount as a decimal string', function () {
    $request = new CreatePaymentRequest(
        document_number: '1',
        payer_account: 'UA1',
        payment_naming: 'Test',
        payment_amount: '1250.50',
        payment_destination: 'Оплата за послуги',
        recipient_account: 'UA2',
    );

    expect($request->toArray()['payment_amount'])->toBe('1250.50');
});
