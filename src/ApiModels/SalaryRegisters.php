<?php

namespace Sashalenz\Privat24BusinessApi\ApiModels;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Sashalenz\Privat24BusinessApi\Enums\PacketStatus;
use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;
use Sashalenz\Privat24BusinessApi\RequestData\Salary\AddRecordRequest;
use Sashalenz\Privat24BusinessApi\RequestData\Salary\CreatePacketRequest;
use Sashalenz\Privat24BusinessApi\RequestData\Salary\ListPacketsRequest;
use Sashalenz\Privat24BusinessApi\RequestData\Salary\RemoveRecordRequest;
use Sashalenz\Privat24BusinessApi\ResponseData\Salary\AddRecordResponse;
use Sashalenz\Privat24BusinessApi\ResponseData\Salary\CreatePacketResponse;
use Sashalenz\Privat24BusinessApi\Types\Packet;
use Sashalenz\Privat24BusinessApi\Types\PacketRecord;

/**
 * pay/maspay — registers: one document that pays many people.
 *
 * The lifecycle is create → add × N → validate, and then a person signs. Like
 * a single payment created through the API, nothing moves until it is signed;
 * unlike a single payment, there is no add-sign endpoint for a register, so the
 * signing happens in Privat24 for Business itself. This client builds the
 * document and hands it over.
 *
 * Lines can be added and removed only while the packet is {@see PacketStatus::CREATED}.
 * After validation the bank marks each line verified or failed, and a failed
 * line stays editable rather than sinking the packet.
 *
 * ⚠️ Whether the packet `reference` reaches the statements feed the way a single
 * payment's payment_pack_ref does (echoed into the transaction's DLR) is not
 * documented. Until one real register proves it either way, do not build
 * reconciliation on the assumption that it does.
 */
class SalaryRegisters extends BaseModel
{
    protected string $method = 'pay';

    /**
     * Open a new register. Returns the packet, whose `reference` every other
     * call here is addressed by.
     *
     * @throws Privat24BusinessApiException
     */
    public function create(CreatePacketRequest $request): CreatePacketResponse
    {
        $this->isPost = true;

        return CreatePacketResponse::from(
            $this
                ->setMethod('maspay/create')
                ->setParams($request)
                ->validate([
                    'group' => ['required', 'string'],
                ])
                ->request()
        );
    }

    /**
     * Add one person and one amount. `receiver` is a SalaryReceiver id from
     * the project directory, not an account number.
     *
     * @throws Privat24BusinessApiException
     */
    public function addRecord(string $reference, AddRecordRequest $request): AddRecordResponse
    {
        $this->isPost = true;

        return AddRecordResponse::from(
            $this
                ->setMethod('maspay/'.rawurlencode($reference).'/add')
                ->setParams($request)
                ->validate([
                    'receiver' => ['required', 'string'],
                    'amount' => ['required', 'numeric', 'gt:0'],
                ])
                ->request()
        );
    }

    /**
     * Drop one line by its own ref. Only while the packet is still editable.
     *
     * @throws Privat24BusinessApiException
     */
    public function removeRecord(string $reference, string $recordRef): bool
    {
        $this->isPost = true;

        $this
            ->setMethod('maspay/'.rawurlencode($reference).'/remove')
            ->setParams(new RemoveRecordRequest(ref: $recordRef))
            ->request();

        return true;
    }

    /**
     * Hand the register to the bank for checking. After this the packet stops
     * being editable and each line comes back verified or failed.
     *
     * @throws Privat24BusinessApiException
     */
    public function validatePacket(string $reference): bool
    {
        $this->isPost = true;

        $this
            ->setMethod('maspay/'.rawurlencode($reference).'/validate')
            ->request();

        return true;
    }

    /**
     * The packet as the bank sees it — chiefly its status.
     *
     * @throws Privat24BusinessApiException
     */
    public function header(string $reference): Packet
    {
        return Packet::from(
            $this
                ->setMethod('maspay/'.rawurlencode($reference).'/header')
                ->request()
        );
    }

    /**
     * The lines, with each one's status and — when it failed — why.
     *
     * @return Collection<int, PacketRecord>
     *
     * @throws Privat24BusinessApiException
     */
    public function records(string $reference, ?int $page = null, ?int $pageSize = null): Collection
    {
        return $this
            ->setMethod('maspay/'.rawurlencode($reference).'/content')
            ->setParams(new ListPacketsRequest(page: $page, pageSize: $pageSize))
            ->request()
            ->map(fn (array $row) => PacketRecord::from($row));
    }

    /**
     * Registers in a window, optionally by status.
     *
     * @return Collection<int, Packet>
     *
     * @throws Privat24BusinessApiException
     */
    public function packets(
        PacketStatus|string|null $status = null,
        ?Carbon $from = null,
        ?Carbon $to = null,
        ?int $page = null,
        ?int $pageSize = null,
    ): Collection {
        return $this
            ->setMethod('apay24/packets/list')
            ->setParams(new ListPacketsRequest(
                status: $status instanceof PacketStatus ? $status->value : $status,
                page: $page,
                pageSize: $pageSize,
                from: $from,
                to: $to,
            ))
            ->request()
            ->map(fn (array $row) => Packet::from($row));
    }
}
