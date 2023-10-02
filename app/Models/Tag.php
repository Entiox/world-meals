<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $timestamps = false;
    public $translatedAttributes = ["title"];
    protected $fillable = ["slug"];

    public function meals() {
        return $this->belongsToMany(Meal::class);
    }
}
