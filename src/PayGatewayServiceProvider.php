<?php

namespace Qionar\PayGateway;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PayGatewayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('pay-gateway')
            ->hasConfigFile()
            ->hasMigration('create_transactions_table')
            ->publishesServiceProvider('PayGatewayServiceProvider')
            ->hasInstallCommand(function(InstallCommand $command) {
               $command
                   ->publishConfigFile()
                   ->publishMigrations()
                   ->copyAndRegisterServiceProviderInApp();
            });
    }
}
