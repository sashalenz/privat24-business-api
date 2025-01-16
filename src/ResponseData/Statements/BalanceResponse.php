<?php

namespace Sashalenz\Privat24BusinessApi\ResponseData\Statements;

use Sashalenz\Privat24BusinessApi\Enums\Status;
use Sashalenz\Privat24BusinessApi\Types\Balance;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class BalanceResponse extends Data
{
    public function __construct(
        public Status $status,
        public string $type,
        public bool $exist_next_page,
        #[DataCollectionOf(Balance::class)]
        public DataCollection $balances,
        public ?string $next_page_id = null,
    ) {
    }
}
