<?php

namespace Sashalenz\Privat24BusinessApi\ApiModels;

use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;
use Sashalenz\Privat24BusinessApi\RequestData\Payments\CreatePaymentRequest;
use Sashalenz\Privat24BusinessApi\RequestData\Payments\GetPaymentRequest;
use Sashalenz\Privat24BusinessApi\ResponseData\Payments\CreatePaymentResponse;
use Sashalenz\Privat24BusinessApi\ResponseData\Payments\GetPaymentResponse;

/**
 * proxy/payment — outgoing payment documents.
 *
 * A payment created via the API does NOT move money: it lands in
 * Privat24 for Business with status "new" and waits for a KEP signature in
 * the cabinet. Until signed it can be deleted via delete().
 */
class Payments extends BaseModel
{
    protected string $method = 'proxy/payment';

    /**
     * Create a payment document (HTTP 201 → payment_ref).
     *
     * @throws Privat24BusinessApiException
     */
    public function create(CreatePaymentRequest $request): CreatePaymentResponse
    {
        $this->isPost = true;

        return CreatePaymentResponse::from(
            $this
                ->setMethod(__FUNCTION__)
                ->setParams($request)
                ->validate([
                    'document_number' => ['required', 'string'],
                    'payer_account' => ['required', 'string'],
                    'payment_naming' => ['required', 'string'],
                    // The API expects a decimal string ('1234.56'); zero is
                    // rejected by the bank, the regex only guards the format.
                    'payment_amount' => ['required', 'string', 'regex:/^\d+(\.\d{1,2})?$/'],
                    'payment_destination' => ['required', 'string', 'min:5', 'max:420'],
                    // No 'nullable': getParams() filters nulls out before
                    // validation, so the fields are either absent or strings.
                    'recipient_account' => ['required_without:recipient_card', 'string'],
                    'recipient_card' => ['required_without:recipient_account', 'string'],
                ])
                ->request()
        );
    }

    /**
     * Current state of a payment document (payment_status etc).
     *
     * @throws Privat24BusinessApiException
     */
    public function get(string $ref): GetPaymentResponse
    {
        return GetPaymentResponse::from(
            $this
                ->setMethod(__FUNCTION__)
                ->setParams(new GetPaymentRequest(ref: $ref))
                ->request()
        );
    }

    /**
     * Delete an unsigned payment document. The API expects POST with the ref
     * in the query string and an empty body, and answers 204 No Content —
     * reaching this method's return statement means success (errors throw).
     *
     * @throws Privat24BusinessApiException
     */
    public function delete(string $ref): bool
    {
        $this->isPost = true;

        $this
            ->setMethod('delete?ref='.rawurlencode($ref))
            ->request();

        return true;
    }
}
