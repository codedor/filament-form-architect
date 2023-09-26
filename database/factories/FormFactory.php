<?php

namespace Codedor\FormArchitect\Database\Factories;

use Codedor\FormArchitect\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'max_submissions' => fake()->randomDigit(),
            'fields' => [],
            'email_subject' => [],
            'email_body' => [],
            'online' => [],
        ];
    }
}
