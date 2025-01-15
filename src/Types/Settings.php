<?php

namespace Sashalenz\Privat24BusinessApi\Types;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class Settings extends Data
{
    public function __construct(
        public string $phase,
        public array $dates_without_oper_day,
        #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
        public Carbon $today,
        #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
        public Carbon $lastday,
        #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
        public Carbon $work_balance,
        #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
        public Carbon $server_date_time,
        #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
        public Carbon $date_final_statement
    ) {}
}
