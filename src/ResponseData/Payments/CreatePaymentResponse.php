<?php

namespace Sashalenz\Privat24BusinessApi\ResponseData\Payments;

use Spatie\LaravelData\Data;

/**
 * HTTP 201 response of proxy/payment/create.
 *
 * payment_ref is the document reference — the same REF that later appears
 * in the statements feed once the payment is signed and executed, so it is
 * the key for deterministic statement matching.
 */
class CreatePaymentResponse extends Data
{
    public function __construct(
        public string $payment_ref,
        public ?string $payment_pack_ref = null,
    ) {}
}
