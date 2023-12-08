<?php

namespace Codedor\FormArchitect\Models;

use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property string $locale
 * @property Form $form
 * @property array $fields
 * @property array $data
 */
class FormSubmission extends Model
{
    public $fillable = [
        'form_id',
        'locale',
        'data',
        'fields',
    ];

    public $casts = [
        'data' => 'json',
        'fields' => 'json',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function toInfolistSchema(): array
    {
        return $this->getDataFieldStack()->map(function (object $field) {
            if (class_exists($field->class) && method_exists($field->class, 'toInfolist')) {
                return $field->class::toInfolist($field->name, $field->value);
            }

            return TextEntry::make($field->name)->getStateUsing(fn () => $field->value);
        })->toArray();
    }

    public function toExcelExport(): array
    {
        return $this->getDataFieldStack()->mapWithKeys(function (object $field) {
            if (class_exists($field->class) && method_exists($field->class, 'toExcelExport')) {
                return [$field->name => $field->class::toExcelExport($field->value)];
            }

            return [$field->name => $field->value];
        })->toArray();
    }

    private function getDataFieldStack(): Collection
    {
        $currentSchema = collect($this->form->fields)->mapWithKeys(fn ($field) => $field);
        $oldSchema = collect($this->fields)->mapWithKeys(fn ($field) => $field);

        return collect($this->data)->map(function ($field) use ($currentSchema, $oldSchema) {
            $fieldClass = get_architect_block(
                array_keys(config('filament-form-architect.default-blocks', [])),
                $currentSchema[$field['key']]['type']
                    ?? $oldSchema[$field['key']]['type']
                    ?? null
            );

            $name = $currentSchema[$field['key']]['data']['working_title']
                ?? $oldSchema[$field['key']]['data']['working_title']
                ?? $field['key'];

            return (object) [
                'name' => $name,
                'value' => $field['value'] ?? null,
                'class' => $fieldClass,
            ];
        });
    }
}
