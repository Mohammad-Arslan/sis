<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
        DB::insert("INSERT INTO `nationalities` (`nationality_name`, `created_at`) VALUES
    ('Pakistani', '" . \Carbon\Carbon::now() . "'),
    ('British', '" . \Carbon\Carbon::now() . "'),
    ('Chinese', '" . \Carbon\Carbon::now() . "');");
    }
}
