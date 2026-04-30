<?php

use Sashalenz\Privat24BusinessApi\Enums\Status;
use Sashalenz\Privat24BusinessApi\ResponseData\Statements\SettingsResponse;
use Sashalenz\Privat24BusinessApi\Types\Settings;

it('parses a typical settings response', function () {
    $response = SettingsResponse::from([
        'status' => 'SUCCESS',
        'type' => 'settings',
        'settings' => [
            'phase' => 'OD',
            'dates_without_oper_day' => ['01.01.2025', '07.01.2025'],
            'today' => '15.03.2025 09:00:00',
            'lastday' => '14.03.2025 18:00:00',
            'work_balance' => 'OD',
            'server_date_time' => '15.03.2025 10:30:42',
            'date_final_statement' => '14.03.2025 23:59:59',
        ],
    ]);

    expect($response->status)->toBe(Status::SUCCESS);
    expect($response->type)->toBe('settings');
    expect($response->settings)->toBeInstanceOf(Settings::class);
    expect($response->settings->phase)->toBe('OD');
    expect($response->settings->dates_without_oper_day)->toEqual(['01.01.2025', '07.01.2025']);
    expect($response->settings->today->format('d.m.Y H:i:s'))->toBe('15.03.2025 09:00:00');
});
