<?php

namespace Sashalenz\Privat24BusinessApi\ResponseData\Salary;

use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;

/** Internal decoder for the bank's salary register list envelopes. */
final class ListResponse
{
    /** @return list<array<string, mixed>> */
    public static function rows(string $body, string $key): array
    {
        $data = json_decode($body, true);

        if (! is_array($data)) {
            throw new Privat24BusinessApiException(is_string($data) ? $data : (trim($body) ?: 'Empty bank response'));
        }

        while (! array_is_list($data)) {
            if (strtoupper((string) ($data['status'] ?? '')) === 'ERROR' || ! empty($data['error']) || isset($data['message'])) {
                $message = $data['message'] ?? $data['error'] ?? 'Bank returned ERROR';
                throw new Privat24BusinessApiException(is_string($message) ? $message : json_encode($message, JSON_UNESCAPED_UNICODE));
            }

            $next = $data[$key] ?? $data['packets'] ?? $data['records'] ?? $data['data'] ?? null;
            if (! is_array($next)) {
                throw new Privat24BusinessApiException(is_string($next) ? $next : 'Unexpected bank list response');
            }
            $data = $next;
        }

        return array_values(array_filter($data, 'is_array'));
    }
}
