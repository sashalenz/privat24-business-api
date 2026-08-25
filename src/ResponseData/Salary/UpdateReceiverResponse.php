<?php

namespace Sashalenz\Privat24BusinessApi\ResponseData\Salary;

use Spatie\LaravelData\Data;

/**
 * Answer of POST pay/mp/update-receiver.
 *
 * `id` is the handle a register line is addressed by — store it against your
 * own record of the person, because nothing else about them will find it again
 * except a search through the whole directory.
 */
class UpdateReceiverResponse extends Data
{
    public function __construct(
        public string $id,
        public ?string $status = null,
    ) {}
}
