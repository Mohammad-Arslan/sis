<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
        DB::insert("INSERT INTO `languages` (`language_name`, `created_at`) VALUES
    ('Urdu', '" . \Carbon\Carbon::now() . "'),
    ('English', '" . \Carbon\Carbon::now() . "'),
    ('Pashto', '" . \Carbon\Carbon::now() . "'),
    ('Mandarin', '" . \Carbon\Carbon::now() . "');");
    }
}
