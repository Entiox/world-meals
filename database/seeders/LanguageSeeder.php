<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("languages")->insert(["locale" => "en", "name" => "English"]);
        DB::table("languages")->insert(["locale" => "de", "name" => "German"]);
        DB::table("languages")->insert(["locale" => "hr", "name" => "Croatian"]);
    }
}
