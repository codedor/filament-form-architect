<?php

namespace Codedor\FormArchitect\Models;

use Codedor\FormArchitect\Database\Factories\FormFactory;
use Codedor\LivewireForms\Fields\Row;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Form extends Model
{
    use HasFactory;
    use HasTranslations;

    public $fillable = [
        'name',
        'email',
        'max_submissions',
        'fields',
        'email_subject',
        'email_body',
        'online',
    ];

    public $translatable = [
        'email_subject',
        'email_body',
        'online',
    ];

    public $casts = [
        'fields' => 'json',
    ];

    protected static function newFactory()
    {
        return new FormFactory;
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function render()
    {
        return view('filament-form-architect::form', [
            'form' => $this,
        ]);
    }

    public function renderFields()
    {
        return view('filament-form-architect::fields', [
            'fields' => $this->getLivewireFormFields(),
        ]);
    }

    public function getLivewireFormFields(): array
    {
        return collect($this->fields)
            ->map(function ($row) {
                $fields = collect($row)
                    ->filter(fn ($field) => $field['data'][app()->getLocale()]['online'] ?? false)
                    ->map(function ($field, $uuid) {
                        return $field['type']::toLivewireForm(
                            $uuid,
                            $field['data'] ?? [],
                            $field['data'][app()->getLocale()] ?? [],
                        )->width($field['width'] ?? 12);
                    });

                if ($fields->isEmpty()) {
                    return null;
                }

                return Row::make()->fields($fields);
            })
            ->filter()
            ->toArray();
    }
}
