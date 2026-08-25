<?php

namespace Sashalenz\Privat24BusinessApi\RequestData\Salary;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * Query for GET pay/apay24/packets/list.
 *
 * Dates are Y-m-d here, unlike the statements endpoints which want d-m-Y —
 * the transformer pins the difference rather than leaving it to the caller.
 */
class ListPacketsRequest extends Data
{
    public function __construct(
        public ?string $status = null,
        public ?int $page = null,
        #[MapOutputName('page-size')]
        public ?int $pageSize = null,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?Carbon $from = null,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?Carbon $to = null,
    ) {}
}
