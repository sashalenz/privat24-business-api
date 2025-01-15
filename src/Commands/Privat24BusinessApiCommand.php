<?php

namespace Sashalenz\Privat24BusinessApi\Commands;

use Illuminate\Console\Command;

class Privat24BusinessApiCommand extends Command
{
    public $signature = 'privat24-business-api';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
