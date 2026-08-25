<?php

namespace Sashalenz\Privat24BusinessApi\Types;

use Sashalenz\Privat24BusinessApi\Enums\PacketStatus;
use Spatie\LaravelData\Data;

/**
 * A register: one document carrying many payments.
 *
 * `reference` is the handle for every other call about it. Whether it also
 * shows up in the statements feed the way a single payment's payment_pack_ref
 * does (echoed into the transaction's DLR) is NOT documented — if it does not,
 * matching the executed debit needs a marker in the payment purpose instead.
 */
class Packet extends Data
{
    public function __construct(
        public string $reference, // посилання на пакет
        public PacketStatus $status,
        public ?string $system = null, // maspay | reqpay
        public ?string $packetName = null, // назва пакета
        public ?string $createdAt = null,
        public ?int $recordCount = null, // кількість рядків
        public ?float $recordAmount = null, // сума пакета
    ) {}
}
