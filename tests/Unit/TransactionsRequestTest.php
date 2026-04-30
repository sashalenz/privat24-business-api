<?php

use Carbon\Carbon;
use Sashalenz\Privat24BusinessApi\RequestData\Statements\TransactionsRequest;

it('encodes dates using Privat24 d-m-Y format', function () {
    $request = new TransactionsRequest(
        startDate: Carbon::parse('2025-01-01'),
        endDate: Carbon::parse('2025-01-31'),
    );

    $array = $request->toArray();

    expect($array['startDate'])->toBe('01-01-2025');
    expect($array['endDate'])->toBe('31-01-2025');
});

it('survives null endDate (open range)', function () {
    $request = new TransactionsRequest(
        startDate: Carbon::parse('2025-01-01'),
    );

    expect($request->endDate)->toBeNull();
});
