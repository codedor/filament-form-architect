<?php

namespace Codedor\FormArchitect\Providers;

use Codedor\FormArchitect\BlockCollection;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FormArchitectServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-form-architect')
            ->setBasePath(__DIR__ . '/../')
            ->hasConfigFile()
            ->hasMigration('create_forms_table')
            ->runsMigrations();
    }

    public function registeringPackage(): void
    {
        $this->app->bind(BlockCollection::class, function () {
            return (new BlockCollection())->fromConfig();
        });
    }
}
