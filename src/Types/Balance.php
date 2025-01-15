<?php

namespace Sashalenz\Privat24BusinessApi\Types;

use Carbon\Carbon;
use Sashalenz\Privat24BusinessApi\Enums\Currency;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;

#[MapInputName(CamelCaseMapper::class)]
class Balance extends Data
{
    public function __construct(
      public string $acc, // номер рахунку
      public Currency $currency, // валюта
      public float $balanceIn, // залишок на рахунку вхідний
      public float $balanceInEq, // залишок на рахунку вхідний (екв. у нац. валюті)
      public float $balanceOut, // залишок на рахунку вихідний
      public float $balanceOutEq, // залишок на рахунку вихідний (екв. у нац. валюті)
      public float $turnoverDebt, // оборот, дебет
      public float $turnoverDebtEq, // оборот, дебет (екв. у нац. валюті)
      public float $turnoverCred, // оборот, кредит
      public float $turnoverCredEq, // оборот, кредит (екв. у нац. валюті)
      public string $bgfIBrnm, // бранч, що залучив контрагента
      public string $brnm, // бранч рахунку
      #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
      public Carbon $dpd, // дата останнього руху за рахунком
      public string $nameACC, // найменування рахунку
      public string $state,
      public string $atp,
      public string $flmn,
      #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
      public Carbon $date_open_acc_reg,
      #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
      public Carbon $date_open_acc_sys,
      #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
      public Carbon $date_close_acc,
      public bool $is_final_bal
    ){ }
}
