<?php

use Carbon\Carbon;
use Sashalenz\Privat24BusinessApi\RequestData\Statements\BalanceRequest;

it('encodes startDate + endDate using Privat24 d-m-Y format', function () {
    $request = new BalanceRequest(
        startDate: Carbon::parse('2025-03-15'),
        endDate: Carbon::parse('2025-03-20'),
    );

    $array = $request->toArray();

    // Privat24 expects DD-MM-YYYY (their docs are explicit about this).
    expect($array['startDate'])->toBe('15-03-2025');
    expect($array['endDate'])->toBe('20-03-2025');
});

it('keeps acc / followId / limit when provided', function () {
    $request = new BalanceRequest(
        acc: 'UA213223130000026007233566001',
        followId: 'token-abc',
        limit: 100,
    );

    $array = $request->toArray();

    expect($array['acc'])->toBe('UA213223130000026007233566001');
    expect($array['followId'])->toBe('token-abc');
    expect($array['limit'])->toBe(100);
});

it('defaults limit to 20', function () {
    $request = new BalanceRequest;

    expect($request->limit)->toBe(20);
});
