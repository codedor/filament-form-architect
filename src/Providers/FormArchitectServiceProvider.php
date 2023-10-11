<?php

namespace Codedor\FormArchitect\Providers;

use Codedor\FormArchitect\BlockCollection;
use Codedor\FormArchitect\Livewire\RenderedForm;
use Livewire\Livewire;
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
            ->hasMigrations([
                '2023_10_10_130632_create_forms_table',
                '2023_10_10_130633_create_form_submissions_table',
            ])
            ->runsMigrations()
            ->hasViews();
    }

    public function registeringPackage(): void
    {
        $this->app->bind(BlockCollection::class, function () {
            return (new BlockCollection())->fromConfig();
        });
    }

    public function bootingPackage()
    {
        Livewire::component('filament-form-architect-rendered-form', RenderedForm::class);
    }
}
