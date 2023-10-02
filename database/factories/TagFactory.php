<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
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
            ],
            "de" => [
                "title" => fake("de_DE") -> word(),
            ],
            "hr" => [
                "title" => fake("hr_HR") -> word(),
            ],
            "slug" => fake() -> word(),
        ];
    }
}
