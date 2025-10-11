<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::insert("INSERT INTO `religions` (`religion_name`, `created_at`) VALUES
    ('Islam', '" . \Carbon\Carbon::now() . "'),
    ('Christian', '" . \Carbon\Carbon::now() . "'),
    ('Hindu', '" . \Carbon\Carbon::now() . "');");
  }
}
