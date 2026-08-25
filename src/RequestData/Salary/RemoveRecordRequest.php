<?php

namespace Sashalenz\Privat24BusinessApi\RequestData\Salary;

use Spatie\LaravelData\Data;

/**
 * Body for POST pay/maspay/{reference}/remove — the line's own ref, which came
 * back from add() and is not the same thing as the packet reference.
 */
class RemoveRecordRequest extends Data
{
    public function __construct(
        public string $ref,
    ) {}
}
