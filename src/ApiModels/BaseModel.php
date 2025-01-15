<?php

namespace Sashalenz\Privat24BusinessApi\ApiModels;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;
use Sashalenz\Privat24BusinessApi\Request;
use Spatie\LaravelData\Data;

abstract class BaseModel
{
    private bool $canBeCached = false;

    private int $cacheSeconds = -1;

    private string $token = '';

    protected string $method = '';

    protected bool $isPost = false;

    protected ?string $proxy = null;

    protected function getHeaders(): array
    {
        return [
            'User-Agent' => 'CRM',
            'token' => $this->token,
            'Content-Type' => 'application/json;charset=utf8',
        ];
    }

    protected function getParams(): array
    {
        if (! isset($this->params)) {
            return [];
        }

        if ($this->params instanceof Data) {
            return $this->params->toArray();
        }

        return $this->params;
    }

    public function cache(int $seconds = -1): self
    {
        $this->canBeCached = true;
        $this->cacheSeconds = $seconds;

        return $this;
    }

    public function token(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function proxy(string $proxy): self
    {
        $this->proxy = $proxy;

        return $this;
    }

    /**
     * @throws Privat24BusinessApiException
     */
    protected function validate(array $rules = []): self
    {
        $validator = Validator::make(
            $this->getParams(),
            $rules
        );

        if ($validator->fails()) {
            throw new Privat24BusinessApiException('Validation exception '.$validator->errors()->first());
        }

        return $this;
    }

    protected function setMethod(string $method): self
    {
        $this->method = collect([$this->method])
            ->add($method)
            ->implode('/');

        return $this;
    }

    /**
     * @throws Privat24BusinessApiException
     */
    protected function request(): Collection
    {
        $request = new Request(
            baseUrl: config('privat24-business-api.api_url'),
            method: $this->method,
            params: $this->getParams(),
            headers: $this->getHeaders(),
            isPost: $this->isPost,
        );

        if ($this->canBeCached) {
            return $request->cache($this->cacheSeconds);
        }

        return $request->make();
    }
}
