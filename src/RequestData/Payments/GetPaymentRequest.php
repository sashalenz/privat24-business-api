<?php

namespace Sashalenz\Privat24BusinessApi\RequestData\Payments;

use Spatie\LaravelData\Data;

class GetPaymentRequest extends Data
{
    public function __construct(
        public string $ref,
    ) {}
}
