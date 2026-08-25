<?php

namespace Sashalenz\Privat24BusinessApi\Types;

use Spatie\LaravelData\Data;

/**
 * A person as the BANK knows them inside a salary project.
 *
 * 🔴 `id` is what a register line is addressed by — not an IBAN and not a card
 * number. Somebody who is not in this directory cannot be paid through a
 * register at all, however complete their bank details are on your side; they
 * have to be put here first with updateReceiver().
 *
 * `pan` comes back masked, so it identifies a card without being one.
 */
class SalaryReceiver extends Data
{
    public function __construct(
        public string $id, // ідентифікатор отримувача в проєкті
        public ?string $pan = null, // картка/рахунок (маскований)
        /** @var array<int, string> прізвище, ім'я, по батькові */
        public array $fio = [],
        public ?string $inn = null, // ІПН
        public ?string $group = null, // код проєкту
        public ?string $tabn = null, // табельний номер (SALARY/STUDENT)
    ) {}

    public function fullName(): string
    {
        return implode(' ', array_filter($this->fio));
    }
}
