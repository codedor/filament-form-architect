<?php

namespace Codedor\FormArchitect\Models;

use Codedor\FormArchitect\Database\Factories\FormFactory;
use Guava\FilamentDrafts\Concerns\HasDrafts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Form extends Model
{
    use HasDrafts;
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
        'fields' => 'array',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new FormFactory();
    }
}
