<?php

namespace Sashalenz\Privat24BusinessApi\RequestData\Salary;

use Spatie\LaravelData\Data;

/**
 * Body for POST pay/maspay/{reference}/add — one person, one amount.
 *
 * `receiver` is a SalaryReceiver id from the bank's directory. `comment` is
 * that line's payment purpose.
 */
class AddRecordRequest extends Data
{
    public function __construct(
        public string $receiver,
        public float $amount,
        public ?string $comment = null,
    ) {}
}
