<?php

namespace Sashalenz\Privat24BusinessApi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Sashalenz\Privat24BusinessApi\Commands\Privat24BusinessApiCommand;

class Privat24BusinessApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('privat24-business-api')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_privat24_business_api_table')
            ->hasCommand(Privat24BusinessApiCommand::class);
    }
}
