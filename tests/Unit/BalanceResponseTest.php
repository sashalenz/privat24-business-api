<?php

use Sashalenz\Privat24BusinessApi\Enums\Currency;
use Sashalenz\Privat24BusinessApi\Enums\Status;
use Sashalenz\Privat24BusinessApi\ResponseData\Statements\BalanceResponse;

it('parses a balance response with one balance row', function () {
    $response = BalanceResponse::from([
        'status' => 'SUCCESS',
        'type' => 'balance',
        'exist_next_page' => false,
        'balances' => [
            [
                'acc' => 'UA213223130000026007233566001',
                'currency' => 'UAH',
                'balanceIn' => 1000.00,
                'balanceInEq' => 1000.00,
                'balanceOut' => 1500.00,
                'balanceOutEq' => 1500.00,
                'turnoverDebt' => 200.00,
                'turnoverDebtEq' => 200.00,
                'turnoverCred' => 700.00,
                'turnoverCredEq' => 700.00,
                'bgfIBrnm' => 'PRIVATBANK',
                'brnm' => 'PRIVATBANK',
                'dpd' => '15.03.2025 10:00:00',
                'nameACC' => 'Operating account',
                'state' => 'open',
                'atp' => 'CURRENT',
                'flmn' => 'N',
                'date_open_acc_reg' => '01.01.2020 00:00:00',
                'date_open_acc_sys' => '01.01.2020 00:00:00',
                'date_close_acc' => '01.01.2099 00:00:00',
                'is_final_bal' => true,
            ],
        ],
        'next_page_id' => null,
    ]);

    expect($response->status)->toBe(Status::SUCCESS);
    expect($response->type)->toBe('balance');
    expect($response->exist_next_page)->toBeFalse();
    expect($response->next_page_id)->toBeNull();
    expect($response->balances)->toHaveCount(1);

    $balance = $response->balances->first();
    expect($balance->acc)->toBe('UA213223130000026007233566001');
    expect($balance->currency)->toBe(Currency::UAH);
    expect($balance->balanceIn)->toBe(1000.00);
    expect($balance->balanceOut)->toBe(1500.00);
    expect($balance->is_final_bal)->toBeTrue();
});

it('parses a paginated response (exist_next_page=true)', function () {
    $response = BalanceResponse::from([
        'status' => 'SUCCESS',
        'type' => 'balance',
        'exist_next_page' => true,
        'balances' => [],
        'next_page_id' => 'page-2-token',
    ]);

    expect($response->exist_next_page)->toBeTrue();
    expect($response->next_page_id)->toBe('page-2-token');
});
