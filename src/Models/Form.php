<?php

namespace Codedor\FormArchitect\Models;

use Codedor\FormArchitect\Database\Factories\FormFactory;
use Codedor\LivewireForms\Fields\Row;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
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
        'completion_message',
        'max_submissions_message',
        'email_subject',
        'email_body',
        'online',
    ];

    public $translatable = [
        'completion_message',
        'max_submissions_message',
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

    public function allowSubmissions(): bool
    {
        return self::maxSubmissionsDisabled() || (
            $this->max_submissions === 0 ||
            $this->submissions()->count() < $this->max_submissions
        );
    }

    public function getMaxSubmissionMessage(): HtmlString
    {
        $message = $this->max_submissions_message;
        if (empty(strip_tags($message))) {
            $message = __html('filament-form-architect::form.max_submissions_message :max', [
                'max' => $this->max_submissions,
            ]);
        }

        return new HtmlString($message);
    }

    public static function maxSubmissionsDisabled(): bool
    {
        return config('filament-form-architect.enable-submission-field') === false;
    }
}
