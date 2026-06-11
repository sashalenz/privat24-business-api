<?php

namespace Sashalenz\Privat24BusinessApi\RequestData\Payments;

use Spatie\LaravelData\Data;

/**
 * Body for POST proxy/payment/create.
 *
 * The created payment lands in Privat24 for Business with status "new"
 * (awaiting signature) — money does not move until an authorized person
 * signs it with their KEP in the cabinet.
 *
 * payment_amount is a string ('1234.56') — the API both accepts and returns
 * amounts as strings; sending a string avoids float formatting artifacts.
 *
 * recipient_account / recipient_card are alternatives: a regular IBAN
 * transfer fills recipient_account, a card top-up fills recipient_card.
 * recipient_nceo is the recipient tax id (ЄДРПОУ/ІПН), '0000000000' for
 * individuals without one.
 */
class CreatePaymentRequest extends Data
{
    public function __construct(
        public string $document_number,
        public string $payer_account,
        public string $payment_naming,
        public string $payment_amount,
        public string $payment_destination,
        public ?string $recipient_account = null,
        public ?string $recipient_card = null,
        public ?string $recipient_nceo = null,
        public ?string $payment_ccy = null,
        public ?string $document_type = null,
        public ?string $payment_date = null,
        public ?string $payment_accept_date = null,
        public ?string $recipient_ifi = null,
        public ?string $recipient_ifi_text = null,
    ) {}
}
