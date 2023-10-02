<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    private $ingredientCount = 10;
    private $tagCount = 10;
    private $categoryCount = 5;
    private $mealCountPerCategory = 100;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = Ingredient::factory()->count($this->ingredientCount)->create();
        $tags = Tag::factory()->count($this->tagCount)->create();

        Category::factory()->count($this->categoryCount)
            ->has(Meal::factory()->count($this->mealCountPerCategory)
                ->hasAttached($ingredients->random(5))
                ->hasAttached($tags->random(3)))
            ->create();

        Meal::factory()->count($this->mealCountPerCategory)
            ->hasAttached($ingredients->random(5))
            ->hasAttached($tags->random(3))->create();
    }
}
