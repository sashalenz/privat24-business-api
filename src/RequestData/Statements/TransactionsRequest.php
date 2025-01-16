<?php

namespace Sashalenz\Privat24BusinessApi\RequestData\Statements;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class TransactionsRequest extends Data
{
    public function __construct(
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'd-m-Y')]
        public ?Carbon $startDate = null,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'd-m-Y')]
        public ?Carbon $endDate = null,
        public ?string $acc = null,
        public ?string $followId = null,
        public ?int $limit = 20,
    ) {}
}
