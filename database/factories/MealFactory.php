<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "en" => [
                "title" => fake("en_US") -> word(),
                "description" => fake("en_US") -> realText(),
            ],
            "de" => [
                "title" => fake("de_DE") -> word(),
                "description" => fake("de_DE") -> realText(),
            ],
            "hr" => [
                "title" => fake("hr_HR") -> word(),
                "description" => fake("hr_HR") -> realText(),
            ],
        ];
    }
}
