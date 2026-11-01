<?php

use Illuminate\Support\Facades\Http;
use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;
use Sashalenz\Privat24BusinessApi\Privat24BusinessApi;

it('unwraps salary register lists and ignores scalar rows', function (string $method, string $wrapper) {
    Http::preventStrayRequests();
    $row = $method === 'packets' ? ['reference' => 'R1', 'status' => 'F'] : ['ref' => 'L1', 'inn' => '1234567890'];
    $key = $method === 'packets' ? 'packets' : 'records';
    $rows = [$row, 'ignore', null, 42];
    $body = match ($wrapper) {
        'bare' => $rows,
        'key' => ['status' => 'SUCCESS', $key => $rows],
        'data' => ['data' => $rows],
        'nested' => ['data' => [$key => $rows]],
        'data.packets' => ['data' => ['packets' => $rows]],
    };
    Http::fake(['*' => Http::response($body)]);
    $api = Privat24BusinessApi::salaryRegisters()->token('test');
    $result = $method === 'packets' ? $api->packets() : $api->records('R1');
    expect($result)->toHaveCount(1);
    Http::assertSent(fn ($request) => $request->method() === 'GET');
})->with(['packets', 'records'])->with(['bare', 'key', 'data', 'nested', 'data.packets']);

it('reports bank errors instead of mapping them as rows', function (string $method, mixed $body, string $message) {
    Http::fake(['*' => Http::response($body)]);
    $api = Privat24BusinessApi::salaryRegisters()->token('test');
    expect(fn () => $method === 'packets' ? $api->packets() : $api->records('R1'))
        ->toThrow(Privat24BusinessApiException::class, $message);
})->with(['packets', 'records'])->with([
    ['Token expired', 'Token expired'],
    ['"Access denied"', 'Access denied'],
    [['status' => 'ERROR', 'message' => 'No access'], 'No access'],
    [['error' => ['message' => 'Invalid token']], 'Invalid token'],
    [['message' => 'Access forbidden'], 'Access forbidden'],
    [['data' => ['status' => 'ERROR', 'message' => 'Nested error']], 'Nested error'],
]);

it('preserves bank access error messages from failed HTTP responses', function (int $status) {
    $message = 'Salary project access denied. '.str_repeat('Request the salary project permission. ', 10);
    Http::fake(['*' => Http::response(['status' => 'ERROR', 'message' => $message], $status)]);
    expect(fn () => Privat24BusinessApi::salaryRegisters()->token('test')->packets())
        ->toThrow(Privat24BusinessApiException::class, $message);
})->with([401, 403]);

it('accepts an empty salary register list', function () {
    Http::fake(['*' => Http::response(['data' => ['packets' => []]])]);
    expect(Privat24BusinessApi::salaryRegisters()->token('test')->packets())->toBeEmpty();
});
