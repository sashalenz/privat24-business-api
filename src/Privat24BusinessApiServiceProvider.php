<?php

namespace Sashalenz\Privat24BusinessApi;

use Sashalenz\Privat24BusinessApi\Commands\Privat24BusinessApiCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class Privat24BusinessApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('privat24-business-api')
            ->hasConfigFile();
    }
}
