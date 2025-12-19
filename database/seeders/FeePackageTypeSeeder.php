<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeePackageTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
        DB::insert("INSERT INTO `fee_package_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
    (1, 'Admission', 'Admission Fee Package', '" . \Carbon\Carbon::now() . "', NULL),
    (2, 'Monthly', 'Monthly Fee Package', '" . \Carbon\Carbon::now() . "', NULL);");
    }
}
