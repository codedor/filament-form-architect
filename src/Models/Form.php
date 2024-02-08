<?php

namespace Codedor\FormArchitect\Models;

use Codedor\FilamentMailTemplates\Facades\MailTemplateFallbacks;
use Codedor\FormArchitect\Database\Factories\FormFactory;
use Codedor\LivewireForms\Fields\Row;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property array $fields
 * @property string $completion_message
 * @property int $max_submissions
 * @property array $max_submissions_message
 * @property array $email_subject
 * @property array $email_body
 * @property array $email_to
 */
class Form extends Model
{
    use HasFactory;
    use HasTranslations;

    public $fillable = [
        'name',
        'email_to',
        'email_from',
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
        'email_to' => 'array',
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
                        $class = get_architect_block(
                            array_keys(config('filament-form-architect.default-blocks', [])),
                            $fieldData['type'],
                        );

                        $field = $class::toLivewireForm(
                            $uuid,
                            $fieldData['data'] ?? [],
                            $fieldData['data'][app()->getLocale()] ?? [],
                        );

                        $config = config("filament-form-architect.default-blocks.{$class}", []);
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

    public static function adminEmailsDisabled(): bool
    {
        return config('filament-form-architect.enable-admin-mails', false) === false;
    }

    public function getEmailsFor(string $type): Collection
    {
        return $this->getAllEmails()
            ->filter(fn ($email) => ($email['type'] ?? $email) === $type)
            ->pluck('email');
    }

    public function getAllEmails(): Collection
    {
        return Collection::wrap(
            ($this->email_to ?: null) ?? [MailTemplateFallbacks::getToMail()]
        );
    }

    public function getFromEmail(): ?string
    {
        return $this->email_from ?? MailTemplateFallbacks::getFromMail();
    }
}
