<?php

namespace Sashalenz\Privat24BusinessApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sashalenz\Privat24BusinessApi\Privat24BusinessApi
 */
class Privat24BusinessApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Sashalenz\Privat24BusinessApi\Privat24BusinessApi::class;
    }
}
