<?php

namespace Sashalenz\Privat24BusinessApi\ApiModels;

use Illuminate\Support\Carbon;
use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;
use Sashalenz\Privat24BusinessApi\RequestData\Statements\BalanceRequest;
use Sashalenz\Privat24BusinessApi\RequestData\Statements\TransactionsRequest;
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
    public function balance(
        Carbon $startDate,
        ?Carbon $endDate = null,
        ?string $acc = null,
        ?string $followId = null,
        ?int $limit = 20,
    ): BalanceResponse {
        return BalanceResponse::from(
            $this
                ->setMethod(__FUNCTION__)
                ->setParams(new BalanceRequest(
                    startDate: $startDate,
                    endDate: $endDate,
                    acc: $acc,
                    followId: $followId,
                    limit: $limit
                ))
                ->request()
        );
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function interimBalance(
        ?string $acc = null,
        ?string $followId = null,
        ?int $limit = 20,
    ): BalanceResponse {
        return BalanceResponse::from(
            $this
                ->setMethod('balance/interim')
                ->setParams(new BalanceRequest(
                    acc: $acc,
                    followId: $followId,
                    limit: $limit
                ))
                ->request()
        );
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function finalBalance(
        ?string $acc = null,
        ?string $followId = null,
        ?int $limit = 20,
    ): BalanceResponse {
        return BalanceResponse::from(
            $this
                ->setMethod('balance/final')
                ->setParams(new BalanceRequest(
                    acc: $acc,
                    followId: $followId,
                    limit: $limit
                ))
                ->request()
        );
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function transactions(
        Carbon $startDate,
        ?Carbon $endDate = null,
        ?string $acc = null,
        ?string $followId = null,
        ?int $limit = 20,
    ): TransactionResponse {
        return TransactionResponse::from(
            $this
                ->setMethod(__FUNCTION__)
                ->setParams(new TransactionsRequest(
                    startDate: $startDate,
                    endDate: $endDate,
                    acc: $acc,
                    followId: $followId,
                    limit: $limit
                ))
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
                ->setParams(new TransactionsRequest(
                    acc: $acc,
                    followId: $followId,
                    limit: $limit
                ))
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
                ->setParams(new TransactionsRequest(
                    acc: $acc,
                    followId: $followId,
                    limit: $limit
                ))
                ->request()
        );
    }
}
