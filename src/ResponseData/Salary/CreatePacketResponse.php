<?php

namespace Sashalenz\Privat24BusinessApi\ResponseData\Salary;

use Sashalenz\Privat24BusinessApi\Enums\PacketStatus;
use Spatie\LaravelData\Data;

/**
 * Answer of POST pay/maspay/create.
 *
 * Only `reference` is relied upon: it is the handle for every other call about
 * the register. The rest is whatever the bank chose to echo back, and a fresh
 * packet is `N` whether or not it says so.
 */
class CreatePacketResponse extends Data
{
    public function __construct(
        public string $reference,
        public ?PacketStatus $status = null,
        public ?string $packetName = null,
    ) {}
}
