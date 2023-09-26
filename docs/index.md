# Build beautiful forms together with our Architect package

## Introduction

Form Architect allows you to build forms in your Filament panel using the [codedor/filament-architect](https://github.com/codedor/filament-architect) package.

## Installation

You can install the package via composer:

```bash
composer require codedor/filament-form-architect
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-form-architect-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-form-architect-config"
```

This is the contents of the published config file:

```php
return [
    'default-blocks' => [
        \Codedor\FormArchitect\Architect\RadioButtonBlock::class,
        \Codedor\FormArchitect\Architect\TextInputBlock::class,
    ],
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-form-architect-views"
```

### Enabling the package

To make the forms work, you have to register the plugin in your Panel provider:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            \Codedor\FormArchitect\Filament\FormArchitectPlugin::make(),
        ]);
    }

```

## Configuration

This package has a couple of config values:

```php
<?php

return [
    'default-blocks' => [
        \Codedor\FormArchitect\Architect\RadioButtonBlock::class,
        \Codedor\FormArchitect\Architect\TextInputBlock::class,
    ],
];
```

### default-blocks

This configures which blocks will be used in the form builder.

