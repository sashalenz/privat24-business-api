<?php

namespace Sashalenz\Privat24BusinessApi\Types;

use Sashalenz\Privat24BusinessApi\Enums\SalaryGroupType;
use Spatie\LaravelData\Data;

/**
 * One mass-payment project the company holds with the bank.
 *
 * `rko` is the bank's fee rate for paying through this project — worth reading
 * before deciding a register is cheaper than a handful of single payments.
 */
class SalaryGroup extends Data
{
    public function __construct(
        public SalaryGroupType $type, // тип проєкту
        public string $name, // назва проєкту
        public ?float $rko = null, // комісія банку за проєктом
    ) {}
}
