<?php

namespace Sashalenz\Privat24BusinessApi;

use Sashalenz\Privat24BusinessApi\ApiModels\Payments;
use Sashalenz\Privat24BusinessApi\ApiModels\Statements;

class Privat24BusinessApi
{
    public static function statements(): Statements
    {
        return new Statements;
    }

    public static function payments(): Payments
    {
        return new Payments;
    }
}
