<?php

namespace Codedor\FormArchitect\Models;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    public $fillable = [
        'form_id',
        'locale',
        'fields',
    ];

    public $casts = [
        'fields' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function toInfolistSchema(): array
    {
        $formFieldMapper = collect($this->form->fields)
            ->mapWithKeys(fn ($field) => $field) // Flatten without losing keys
            ->mapWithKeys(fn ($field, $key) => [$key => $field['data']['working_title'] ?? null]);

        return collect($this->fields)
            ->mapWithKeys(fn ($value, $key) => [$formFieldMapper[$key] ?? $key => $value])
            ->map(function ($value, $key) {
                return TextEntry::make($key)->getStateUsing(fn () => $value);
            })
            ->toArray();
    }
}
