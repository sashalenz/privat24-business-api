<?php

namespace Sashalenz\Privat24BusinessApi\ResponseData\Payments;

use Spatie\LaravelData\Data;

/**
 * HTTP 201 response of proxy/payment/create.
 *
 * payment_ref is the payment document handle (used by proxy/payment/get and
 * proxy/payment/delete). It does NOT appear in the statements feed, so it
 * cannot be used to match the executed debit.
 *
 * payment_pack_ref is the key for deterministic statement matching: for a
 * payment created through the API ("Автоклієнт"), the bank echoes it into the
 * executed transaction's DLR field ({@see \Sashalenz\Privat24BusinessApi\Types\Transaction::$DLR}).
 */
class CreatePaymentResponse extends Data
{
    public function __construct(
        public string $payment_ref,
        public ?string $payment_pack_ref = null,
    ) {}
}
