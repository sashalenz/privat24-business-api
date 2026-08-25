<?php

namespace Sashalenz\Privat24BusinessApi\RequestData\Salary;

use Sashalenz\Privat24BusinessApi\Enums\SalaryGroupType;
use Spatie\LaravelData\Data;

/**
 * Body for POST pay/maspay/create.
 *
 * `salary` marks the register as wages rather than another kind of mass
 * payment, which is what the bank reports to the tax authority — so it is a
 * declaration, not a formatting flag.
 */
class CreatePacketRequest extends Data
{
    public function __construct(
        public SalaryGroupType $group,
        public bool $salary = true,
        public ?string $packetName = null,
    ) {}
}
