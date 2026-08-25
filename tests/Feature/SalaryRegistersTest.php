<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\Privat24BusinessApi\Enums\PacketRecordStatus;
use Sashalenz\Privat24BusinessApi\Enums\PacketStatus;
use Sashalenz\Privat24BusinessApi\Enums\SalaryGroupType;
use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;
use Sashalenz\Privat24BusinessApi\Privat24BusinessApi;
use Sashalenz\Privat24BusinessApi\RequestData\Salary\AddRecordRequest;
use Sashalenz\Privat24BusinessApi\RequestData\Salary\CreatePacketRequest;
use Sashalenz\Privat24BusinessApi\RequestData\Salary\UpdateReceiverRequest;
use Sashalenz\Privat24BusinessApi\Types\Packet;
use Sashalenz\Privat24BusinessApi\Types\PacketRecord;
use Sashalenz\Privat24BusinessApi\Types\SalaryReceiver;

/*
 | Salary registers — one document that pays many people.
 |
 | The thing worth pinning is that a register is addressed by the bank's own
 | recipient id, not by an account number. Everything else follows from it: a
 | person has to be in the project directory before a line can name them, and
 | the id that comes back is the only handle to them afterwards.
 */

beforeEach(function () {
    Http::preventStrayRequests();
});

it('lists the projects a company holds, with the bank fee for each', function () {
    Http::fake([
        'acp.privatbank.ua/api/pay/mp/list-groups' => Http::response([
            ['type' => 'SALARY', 'name' => 'Зарплатний проєкт', 'rko' => 0.05],
        ]),
    ]);

    $groups = Privat24BusinessApi::salaryProject()->token('t')->groups();

    expect($groups)->toHaveCount(1)
        ->and($groups->first()->type)->toBe(SalaryGroupType::SALARY)
        ->and($groups->first()->rko)->toBe(0.05);
});

it('finds a person already in the project without paging through everybody', function () {
    Http::fake([
        'acp.privatbank.ua/api/pay/mp/list-receivers*' => Http::response([
            [
                'id' => 'rcv-1',
                'pan' => '4149**1111',
                'fio' => ['Коваль', 'Олена', 'Петрівна'],
                'inn' => '1234567890',
                'group' => 'SALARY',
                'tabn' => '42',
            ],
        ]),
    ]);

    $receivers = Privat24BusinessApi::salaryProject()
        ->token('t')
        ->receivers(SalaryGroupType::SALARY, filter: '1234567890');

    expect($receivers->first())->toBeInstanceOf(SalaryReceiver::class)
        ->and($receivers->first()->id)->toBe('rcv-1')
        ->and($receivers->first()->fullName())->toBe('Коваль Олена Петрівна');

    Http::assertSent(fn (Request $r) => $r['group'] === 'SALARY' && $r['filter'] === '1234567890');
});

it('sends page-size with the hyphen the API asks for, not the property name', function () {
    Http::fake(['acp.privatbank.ua/api/pay/mp/list-receivers*' => Http::response([])]);

    Privat24BusinessApi::salaryProject()->token('t')->receivers('SALARY', page: 0, pageSize: 100);

    // A PHP property cannot be called page-size, so the mapping is the only
    // thing standing between the caller and a silently ignored page size.
    Http::assertSent(fn (Request $r) => $r['page-size'] === 100 && ! isset($r['pageSize']));
});

it('puts a person into the project and hands back the id a register line needs', function () {
    Http::fake([
        'acp.privatbank.ua/api/pay/mp/update-receiver' => Http::response([
            'status' => 'OK',
            'id' => 'rcv-77',
        ]),
    ]);

    $response = Privat24BusinessApi::salaryProject()
        ->token('t')
        ->updateReceiver(new UpdateReceiverRequest(
            pan: 'UA743052990000026002000000002',
            group: SalaryGroupType::SALARY,
            fio: ['Мельник', 'Тарас'],
            inn: '9876543210',
            tabn: '7',
        ));

    expect($response->id)->toBe('rcv-77');

    Http::assertSent(fn (Request $r) => $r->method() === 'POST'
        && $r['group'] === 'SALARY'
        && $r['fio'] === ['Мельник', 'Тарас']);
});

it('opens a register and addresses every later call by its reference', function () {
    Http::fake([
        'acp.privatbank.ua/api/pay/maspay/create' => Http::response([
            'reference' => 'pkt-1',
            'status' => 'N',
        ]),
    ]);

    $packet = Privat24BusinessApi::salaryRegisters()
        ->token('t')
        ->create(new CreatePacketRequest(group: SalaryGroupType::SALARY, salary: true, packetName: 'Тиждень 34'));

    expect($packet->reference)->toBe('pkt-1')
        ->and($packet->status)->toBe(PacketStatus::CREATED);

    Http::assertSent(fn (Request $r) => $r['salary'] === true && $r['packetName'] === 'Тиждень 34');
});

it('adds a line naming the recipient id rather than an account', function () {
    Http::fake([
        'acp.privatbank.ua/api/pay/maspay/pkt-1/add' => Http::response(['ref' => 'rec-9']),
    ]);

    $record = Privat24BusinessApi::salaryRegisters()
        ->token('t')
        ->addRecord('pkt-1', new AddRecordRequest(receiver: 'rcv-77', amount: 1250.50, comment: 'Зарплата'));

    expect($record->ref)->toBe('rec-9');

    Http::assertSent(fn (Request $r) => $r['receiver'] === 'rcv-77' && $r['amount'] === 1250.50);
});

it('refuses a line with no amount before the bank has to', function () {
    Http::fake(['acp.privatbank.ua/*' => Http::response([])]);

    expect(fn () => Privat24BusinessApi::salaryRegisters()
        ->token('t')
        ->addRecord('pkt-1', new AddRecordRequest(receiver: 'rcv-77', amount: 0)))
        ->toThrow(Privat24BusinessApiException::class);
});

it('removes a line by its own ref, not the packet reference', function () {
    Http::fake(['acp.privatbank.ua/api/pay/maspay/pkt-1/remove' => Http::response([], 200)]);

    Privat24BusinessApi::salaryRegisters()->token('t')->removeRecord('pkt-1', 'rec-9');

    Http::assertSent(fn (Request $r) => $r->method() === 'POST' && $r['ref'] === 'rec-9');
});

it('reads the lines back with the reason a failed one failed', function () {
    Http::fake([
        'acp.privatbank.ua/api/pay/maspay/pkt-1/content*' => Http::response([
            ['ref' => 'rec-9', 'status' => 'R', 'amount' => 1250.50, 'inn' => '9876543210'],
            ['ref' => 'rec-10', 'status' => 'N$', 'amount' => 10.0, 'errorCode' => 'CARD_CLOSED'],
        ]),
    ]);

    $records = Privat24BusinessApi::salaryRegisters()->token('t')->records('pkt-1');

    expect($records)->toHaveCount(2)
        ->and($records->first())->toBeInstanceOf(PacketRecord::class)
        ->and($records->first()->status)->toBe(PacketRecordStatus::VERIFIED)
        // A failed line stays editable rather than sinking the packet, so the
        // caller needs to be able to tell which one and why.
        ->and($records->last()->status)->toBe(PacketRecordStatus::ERROR)
        ->and($records->last()->errorCode)->toBe('CARD_CLOSED');
});

it('lists packets in a window with Y-m-d dates, not the d-m-Y statements use', function () {
    Http::fake(['acp.privatbank.ua/api/pay/apay24/packets/list*' => Http::response([
        ['reference' => 'pkt-1', 'status' => 'F', 'recordCount' => 12, 'recordAmount' => 15000.0],
    ])]);

    $packets = Privat24BusinessApi::salaryRegisters()
        ->token('t')
        ->packets(PacketStatus::PROCESSED, from: Carbon\Carbon::parse('2026-08-01'), to: Carbon\Carbon::parse('2026-08-31'));

    expect($packets->first())->toBeInstanceOf(Packet::class)
        ->and($packets->first()->status)->toBe(PacketStatus::PROCESSED)
        ->and($packets->first()->recordCount)->toBe(12);

    Http::assertSent(fn (Request $r) => $r['from'] === '2026-08-01' && $r['status'] === 'F');
});

it('knows which states still let a register be edited and which are the end of it', function () {
    expect(PacketStatus::CREATED->isEditable())->toBeTrue()
        ->and(PacketStatus::VALIDATED->isEditable())->toBeFalse()
        ->and(PacketStatus::PROCESSED->isFinal())->toBeTrue()
        ->and(PacketStatus::SIGNED_BY_ACCOUNTANT->isFinal())->toBeFalse();
});
