<?php

namespace Wotz\FormArchitect\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wotz\FormArchitect\Models\Form;

class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email_from' => fake()->email(),
            'max_submissions' => fake()->randomDigit(),
            'email_to' => [],
            'fields' => [],
            'completion_message' => [],
            'email_subject' => [],
            'email_body' => [],
            'online' => [],
        ];
    }
}
