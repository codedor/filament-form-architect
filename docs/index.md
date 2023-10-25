# Build beautiful forms together with our Architect package

## Introduction

Form Architect allows you to build forms in your Filament panel using the [codedor/filament-architect](https://github.com/codedor/filament-architect) package.

## Installation

You can install the package via composer:

```bash
composer require codedor/filament-form-architect
```

You can publish (optional) and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-form-architect-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-form-architect-config"
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

## Creating a block/field

See our [Architect](https://github.com/codedor/filament-architect) package for how to create new blocks.

The only difference here is that we have a `toLivewireForm` function instead of a `render` function, which returns the Livewire form component.

For example, the `TextInputBlock`:

```php
public static function toLivewireForm(string $uuid, array $data, array $translated): Field
{
    return TextField::make($uuid)
        ->label($translated['label'])
        ->required($data['is_required'] ?? false)
        ->rules($data['is_required'] ? 'required' : null)
        ->gdprNotice(new HtmlString($translated['gdpr_notice'] ?? null))
        ->type($data['type'] ?? 'text')
        ->placeholder(($data['hide_placeholder'] ?? false) ? '' : ($translated['label'] ?? null))
        ->validationMessages([
            'required' => __('validation.required', [
                'attribute' => $translated['label'],
            ]),
        ]);
}
```

We also have a `toExcelExport` function, which returns the value of the field for the excel export.
For example, the `CheckboxBlock`:

```php
public static function toExcelExport(mixed $value): string
{
    return $value ? 'Yes' : 'No';
}
```

## Configuration

This package has a couple of config values:

```php
use Codedor\FormArchitect\Architect;

return [
    'enable-submission-field' => false,
    'default-row-attributes' => [
        'divClass' => 'row',
    ],
    'default-blocks' => [
        Architect\TitleBlock::class => [],
        Architect\TextInputBlock::class => [],
        Architect\TextareaBlock::class => [],
        Architect\RadioButtonBlock::class => [],
        Architect\FileInputBlock::class => [],
        Architect\CheckboxBlock::class => [],
    ],
];
```

### enable-submission-field

If set to true, the form resource will have an extra `Max submissions` field, where you can set how many times the form can be submitted. If set to false, the field will not be shown.

An extra `Max submissions reached` field will also be available under the translations tab, which you can use to customize the message that will be shown when the max submissions is reached.

### default-row-attributes

This configures the default attributes for the row block. For example the `divClass` attribute is used to set the class of the row div.

### default-blocks

This configures which blocks will be used in the form builder, see our [Architect](https://github.com/codedor/filament-architect) package for more information about blocks.

A slight difference with the normal Architect blocks is that we can pass an array per block, which will be used as the default attributes for that block. For example:

```php
'default-blocks' => [
    Architect\TitleBlock::class => [
        'divClass' => 'mb-3',
        'headingClass' => 'form__title',
    ],
    ...
],
```

See our [Livewire Forms](https://github.com/codedor/laravel-livewire-forms) package for an overview of available attributes.
