<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassGroupSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
        DB::insert("INSERT INTO `class_groups` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
    (1, 'Pre Nursery  to Five', 'Pre Nursery to Five', '2022-03-16 07:53:24', '2022-03-16 12:00:07', NULL),
    (2, 'Pre Nursery to O Level', 'Pre Nursery to O Level', '2022-03-16 12:04:34', '2022-03-16 12:04:34', NULL);");
    }
}
