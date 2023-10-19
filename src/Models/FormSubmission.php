<?php

namespace Codedor\FormArchitect\Models;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Model;

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

            if (class_exists($fieldClass) && method_exists($fieldClass, 'toInfolist')) {
                return $fieldClass::toInfolist($name, $field['value']);
            }

            return TextEntry::make($name)->getStateUsing(fn () => $field['value']);
        })->toArray();
    }
}
