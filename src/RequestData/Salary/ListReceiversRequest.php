<?php

namespace Sashalenz\Privat24BusinessApi\RequestData\Salary;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

/**
 * Query for GET pay/mp/list-receivers.
 *
 * `page-size` carries a hyphen, which is not a PHP identifier — hence the
 * output mapping rather than a differently named property.
 */
class ListReceiversRequest extends Data
{
    public function __construct(
        public string $group,
        public ?int $page = null,
        #[MapOutputName('page-size')]
        public ?int $pageSize = null,
        public ?string $filter = null, // пошук по ПІБ / ІПН / табельному
    ) {}
}
