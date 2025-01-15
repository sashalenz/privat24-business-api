<?php

namespace Sashalenz\Privat24BusinessApi\ApiModels;

use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;
use Sashalenz\Privat24BusinessApi\ResponseData\Statements\BalanceResponse;
use Sashalenz\Privat24BusinessApi\ResponseData\Statements\SettingsResponse;
use Sashalenz\Privat24BusinessApi\ResponseData\Statements\TransactionResponse;

class Statements extends BaseModel
{
    protected string $method = 'statements';

    /**
     * @throws Privat24BusinessApiException
     */
    public function settings(): SettingsResponse
    {
        return SettingsResponse::from(
            $this
                ->setMethod(__FUNCTION__)
                ->request()
        );
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function balance(): BalanceResponse {
        return BalanceResponse::from(
            $this
                ->setMethod(__FUNCTION__)
                ->request()
        );
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function interimBalance(): BalanceResponse {
        return BalanceResponse::from(
            $this
                ->setMethod('balance/interim')
                ->request()
        );
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function finalBalance(): BalanceResponse {
        return BalanceResponse::from(
            $this
                ->setMethod('balance/final')
                ->request()
        );
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function transactions(
        string $startDate,
        ?string $acc = null,
        ?string $endDate = null,
        ?string $followId = null,
        ?int $limit = 20,
    ): TransactionResponse {
        return TransactionResponse::from(
            $this
                ->setMethod(__FUNCTION__)
                ->request()
        );
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function interimTransactions(
        ?string $acc = null,
        ?string $followId = null,
        ?int $limit = 20,
    ): TransactionResponse {
        return TransactionResponse::from(
            $this
                ->setMethod('transactions/interim')
                ->request()
        );
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function finalTransactions(
        ?string $acc = null,
        ?string $followId = null,
        ?int $limit = 20,
    ): TransactionResponse {
        return TransactionResponse::from(
            $this
                ->setMethod('transactions/final')
                ->request()
        );
    }
}
