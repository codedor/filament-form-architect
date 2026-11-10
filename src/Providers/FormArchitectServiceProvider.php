<?php

namespace Wotz\FormArchitect\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wotz\FormArchitect\Livewire\RenderedForm;

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
                '2023_10_10_130634_add_tiptap_fields_to_forms',
                '2024_02_08_130634_add_email_to_and_from_fields_to_forms',
            ])
            ->runsMigrations()
            ->hasViews();
    }

    public function bootingPackage()
    {
        Livewire::component('filament-form-architect-rendered-form', RenderedForm::class);
    }
}
