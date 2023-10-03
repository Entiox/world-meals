<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function mealTranslations() 
    {
        return $this->hasMany(MealTranslation::class);
    }

    public function ingredientTranslations() 
    {
        return $this->hasMany(Ingredient::class);
    }

    public function tagTranslations() 
    {
        return $this->hasMany(TagTranslation::class);
    }

    public function categoryTranslations() 
    {
        return $this->hasMany(CategoryTranslation::class);
    }
}
