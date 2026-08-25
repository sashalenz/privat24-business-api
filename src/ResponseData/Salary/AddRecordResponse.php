<?php

namespace Sashalenz\Privat24BusinessApi\ResponseData\Salary;

use Spatie\LaravelData\Data;

/** Answer of POST pay/maspay/{reference}/add — the new line's own handle. */
class AddRecordResponse extends Data
{
    public function __construct(
        public string $ref,
    ) {}
}
