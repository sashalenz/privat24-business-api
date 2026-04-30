<?php

use Sashalenz\Privat24BusinessApi\Enums\Currency;
use Sashalenz\Privat24BusinessApi\Enums\Status;

it('exposes a Status enum with both API outcomes', function () {
    expect(Status::SUCCESS->value)->toBe('SUCCESS');
    expect(Status::ERROR->value)->toBe('ERROR');
});

it('exposes a Currency enum', function () {
    expect(Currency::UAH->value)->toBe('UAH');
});

it('round-trips Status via from()', function () {
    expect(Status::from('SUCCESS'))->toBe(Status::SUCCESS);
    expect(Status::from('ERROR'))->toBe(Status::ERROR);
});

it('round-trips Currency via from()', function () {
    expect(Currency::from('UAH'))->toBe(Currency::UAH);
});
