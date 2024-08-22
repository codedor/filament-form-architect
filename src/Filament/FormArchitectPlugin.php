<?php

namespace Codedor\FormArchitect\Filament;

use Codedor\FormArchitect\Filament\Resources\FormResource;
use Codedor\FormArchitect\Filament\Resources\FormSubmissionResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FormArchitectPlugin implements Plugin
{
    protected bool $hasFormResource = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-form-architect';
    }

    public function register(Panel $panel): void
    {
        if ($this->hasFormResource()) {
            $panel->resources([
                FormResource::class,
                FormSubmissionResource::class,
            ]);
        }
    }

    public function boot(Panel $panel): void {}

    public function formResource(bool $condition = true): static
    {
        // This is the setter method, where the user's preference is
        // stored in a property on the plugin object.
        $this->hasFormResource = $condition;

        // The plugin object is returned from the setter method to
        // allow fluent chaining of configuration options.
        return $this;
    }

    public function hasFormResource(): bool
    {
        // This is the getter method, where the user's preference
        // is retrieved from the plugin property.
        return $this->hasFormResource;
    }
}
