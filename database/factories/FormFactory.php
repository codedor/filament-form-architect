<?php

namespace Codedor\FormArchitect\Database\Factories;

use Codedor\FilamentRedirects\Models\Redirect;
use Codedor\FormArchitect\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Form>
 */
class FormFactory extends Factory
{
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
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
