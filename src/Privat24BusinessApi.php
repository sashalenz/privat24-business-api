<?php

namespace Sashalenz\Privat24BusinessApi;

use Sashalenz\Privat24BusinessApi\ApiModels\Payments;
use Sashalenz\Privat24BusinessApi\ApiModels\SalaryProject;
use Sashalenz\Privat24BusinessApi\ApiModels\SalaryRegisters;
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

    /** The directory of people a mass-payment project may pay. */
    public static function salaryProject(): SalaryProject
    {
        return new SalaryProject;
    }

    /** Registers — one document that pays many people. */
    public static function salaryRegisters(): SalaryRegisters
    {
        return new SalaryRegisters;
    }
}
