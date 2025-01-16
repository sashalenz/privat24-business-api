<?php

namespace Sashalenz\Privat24BusinessApi;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;

final class Request
{
    private const int TIMEOUT = 10;

    private const int RETRY_TIMES = 3;

    private const int RETRY_SLEEP = 100;

    public function __construct(
        private readonly string $method,
        private readonly array $params,
        private readonly array $headers,
        private readonly bool $isPost,
        private readonly ?string $proxy = null,
    ) {
    }

    /**
     * @throws Privat24BusinessApiException
     */
    public function make(): Collection
    {
        try {
            return Http::timeout(self::TIMEOUT)
                ->retry(
                    self::RETRY_TIMES,
                    self::RETRY_SLEEP
                )
                ->baseUrl(config('privat24-business-api.api_url'))
                ->withHeaders($this->headers)
                ->when(
                    !is_null($this->proxy),
                    fn ($request) => $request->withOptions([
                        'proxy' => $this->proxy
                    ])
                )
                ->when(
                    $this->isPost,
                    fn ($request) => $request
                        ->asJson()
                        ->post(
                            $this->method,
                            $this->params
                        ),
                    fn ($request) => $request->get(
                        $this->method,
                        $this->params
                    ),
                )
                ->throw()
                ->collect();
        } catch (RequestException $e) {
            throw new Privat24BusinessApiException('API Exception: ' . $e->getMessage(), $e->getCode());
        }
    }

    public function cache(int $seconds = -1): Collection
    {
        if ($seconds === -1) {
            return Cache::rememberForever($this->getCacheKey(), fn () => $this->make());
        }

        return Cache::remember($this->getCacheKey(), $seconds, fn () => $this->make());
    }

    private function getCacheKey(): string
    {
        return collect([
            'p24api',
            $this->method,
            base64_encode(serialize($this->params)),
        ])
            ->implode('_');
    }
}
