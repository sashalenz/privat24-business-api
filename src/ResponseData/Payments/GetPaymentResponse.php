<?php

namespace Sashalenz\Privat24BusinessApi\ResponseData\Payments;

use Spatie\LaravelData\Data;

/**
 * Response of GET proxy/payment/get?ref=.
 *
 * Only the fields needed for status tracking are typed; the API returns the
 * full payment document (plus signature metadata like level_sign /
 * fields_for_sign) — extra keys are ignored by laravel-data.
 *
 * Known payment_status values: 'new' (awaiting signature). The full
 * vocabulary is not published — treat the field as an opaque string and
 * keep raw access via toArray() on the calling side when needed.
 */
class GetPaymentResponse extends Data
{
    public function __construct(
        public string $payment_ref,
        public ?string $payment_status = null,
        public ?string $document_number = null,
        public ?string $payment_amount = null,
        /** @var array<string, bool>|null */
        public ?array $level_sign = null,
        /** @var array<string, mixed>|null */
        public ?array $fields_for_sign = null,
    ) {}
}
