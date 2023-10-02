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
        DB::table("languages")->insert(["locale" => "en_US", "name" => "English"]);
        DB::table("languages")->insert(["locale" => "de_DE", "name" => "German"]);
        DB::table("languages")->insert(["locale" => "hr_HR", "name" => "Croatian"]);
    }
}
