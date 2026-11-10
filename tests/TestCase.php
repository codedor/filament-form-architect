<?php

namespace Wotz\FormArchitect\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Panel;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Wotz\FilamentArchitect\Providers\FilamentArchitectServiceProvider;
use Wotz\FilamentMailTemplates\Providers\FilamentMailTemplatesServiceProvider;
use Wotz\FormArchitect\Filament\FormArchitectPlugin;
use Wotz\FormArchitect\Providers\FormArchitectServiceProvider;
use Wotz\LivewireForms\LivewireFormsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Wotz\\FormArchitect\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentArchitectServiceProvider::class,
            FormArchitectServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            SchemasServiceProvider::class,
            InfolistsServiceProvider::class,
            FormsServiceProvider::class,
            ActionsServiceProvider::class,
            WidgetsServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            LivewireServiceProvider::class,
            LivewireFormsServiceProvider::class,
            FilamentMailTemplatesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $panel = new Panel;
        $panel
            ->id('resource-test')
            ->default(true)
            ->plugin(FormArchitectPlugin::make());

        Filament::registerPanel($panel);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Fixtures/Database/migrations/create_users_table.php');
    }
}
