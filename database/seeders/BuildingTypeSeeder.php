<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::insert("INSERT INTO `building_types` (`id`, `type_name`, `type_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
    (1, 'Owned', 'Some description goes here.', '2022-04-06 02:35:53', '2022-04-06 02:35:53', NULL),
    (2, 'Rented', 'Some description goes here.', '2022-04-06 02:36:11', '2022-04-06 02:36:11', NULL);");
  }
}
