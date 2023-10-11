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
        'data' => 'array',
        'fields' => 'array',
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
            $fieldClass = $currentSchema[$field['key']]['type']
                ?? $oldSchema[$field['key']]['type']
                ?? null;

            $name = $currentSchema[$field['key']]['data']['working_title']
                ?? $oldSchema[$field['key']]['data']['working_title']
                ?? $field['key'];

            if (class_exists($fieldClass)) {
                return $fieldClass::toInfolist($name, $field['value']);
            }

            return TextEntry::make($field['key'])->getStateUsing(fn () => $field['value']);
        })->toArray();
    }
}
