<?php

namespace Sashalenz\Privat24BusinessApi\RequestData\Salary;

use Sashalenz\Privat24BusinessApi\Enums\SalaryGroupType;
use Spatie\LaravelData\Data;

/**
 * Body for POST pay/mp/update-receiver — put a person into a salary project,
 * or correct the details of somebody already in it.
 *
 * This is the step that has no equivalent in single payments: there you name an
 * IBAN and the bank pays it. Here the bank keeps its own directory, and a
 * register can only address people who are in it.
 *
 * `inn` is required when the card is not the bank's own. `tabn` is the employee
 * number and only means anything for SALARY and STUDENT projects.
 */
class UpdateReceiverRequest extends Data
{
    public function __construct(
        public string $pan, // картка або рахунок
        public SalaryGroupType $group,
        /** @var array<int, string> прізвище, ім'я, по батькові */
        public array $fio,
        public ?string $inn = null,
        public ?string $tabn = null,
    ) {}
}
