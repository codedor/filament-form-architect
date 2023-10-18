<?php

namespace Codedor\FilamentFormArchitect\Engines;

use Codedor\FilamentArchitect\Engines\RenderEngine;
use Illuminate\View\View;

class FormArchitect extends RenderEngine
{
    public function toHtml(): View
    {
        return collect($this->fields)
            ->map(function ($row) {
                $fields = collect($row)
                    ->filter(fn ($field) => $field['data'][app()->getLocale()]['online'] ?? false)
                    ->map(function ($fieldData, $uuid) {
                        $field = $fieldData['type']::toLivewireForm(
                            $uuid,
                            $fieldData['data'] ?? [],
                            $fieldData['data'][app()->getLocale()] ?? [],
                        );

                        $config = config("filament-form-architect.default-blocks.{$fieldData['type']}", []);
                        foreach ($config as $key => $value) {
                            $field->{$key}($value);
                        }

                        return $field->width($fieldData['width'] ?? 12);
                    });

                if ($fields->isEmpty()) {
                    return null;
                }

                $row = Row::make()->fields($fields);

                $config = config('filament-form-architect.default-row-attributes', []);
                foreach ($config as $key => $value) {
                    $row->{$key}($value);
                }

                return $row;
            })
            ->filter()
            ->toArray();
    }
}
