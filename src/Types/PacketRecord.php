<?php

namespace Sashalenz\Privat24BusinessApi\Types;

use Sashalenz\Privat24BusinessApi\Enums\PacketRecordStatus;
use Spatie\LaravelData\Data;

/**
 * One line of a register — one person, one amount.
 *
 * `ref` is the line's own handle, and the only thing removeRecipient() accepts.
 * `errorCode` is filled when the bank refused to validate the line; the line
 * stays editable in that state rather than sinking the packet.
 */
class PacketRecord extends Data
{
    public function __construct(
        public string $ref, // посилання на рядок
        public ?PacketRecordStatus $status = null,
        /** @var array<int, string>|string */
        public array|string $fio = [],
        public ?string $inn = null,
        public ?string $cardNumber = null,
        public ?float $amount = null,
        public ?string $errorCode = null, // причина, чому рядок не пройшов
        public ?string $tabNo = null,
    ) {}
}
