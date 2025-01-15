<?php

namespace Sashalenz\Privat24BusinessApi\ResponseData\Statements;

use Sashalenz\Privat24BusinessApi\Enums\Status;
use Sashalenz\Privat24BusinessApi\Types\Settings;
use Spatie\LaravelData\Data;

class SettingsResponse extends Data
{
    public function __construct(
        public Status $status,
        public string $type,
        public Settings $settings,
    ) {
    }
}
