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
    ) {}

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
                    ! is_null($this->proxy),
                    fn ($request) => $request->withOptions([
                        'proxy' => $this->proxy,
                    ])
                )
                ->when(
                    $this->isPost,
                    // bodyFormat('json'), NOT asJson(): asJson() also calls
                    // contentType('application/json'), silently overriding the
                    // 'application/json;charset=utf8' header the API expects.
                    // Empty params → POST with no body at all (payment/delete
                    // expects a truly empty body). post() can't do that — it
                    // always wraps $data into the body format, so even an empty
                    // array becomes the JSON string '[]'; bare send() skips the
                    // body option entirely.
                    fn ($request) => $this->params === []
                        ? $request->send('POST', $this->method)
                        : $request
                            ->bodyFormat('json')
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
            throw new Privat24BusinessApiException('API Exception: '.$e->getMessage(), $e->getCode());
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
