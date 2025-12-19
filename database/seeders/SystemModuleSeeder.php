<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemModuleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
      // Skip if data already exists
        if (DB::table('system_modules')->count() > 0) {
            $this->command->info('System modules already exist, skipping...');
            return;
        }

        DB::insert("INSERT INTO `system_modules` (`id`, `name`, `description`, `parent_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
    (1, 'Employee', 'Manage all types of employee details', 0, '2022-03-30 10:26:20', '2022-03-30 10:26:20', NULL),
    (2, 'Employee Basic', 'Employee basic information in system', 1, '2022-03-30 13:35:26', '2022-04-13 10:48:13', NULL),
    (3, 'Employee Service Info', 'Employee Service Information in the system', 1, '2022-03-30 14:44:54', '2022-04-13 10:48:30', NULL),
    (4, 'Employee Company Info', 'Employee Company Information in the system', 1, '2022-03-30 14:45:25', '2022-04-13 10:48:47', NULL),
    (9, 'Cities', 'Manage city module', 0, '2022-04-13 12:28:29', '2022-04-13 12:28:29', NULL),
    (10, 'Class', 'Manage class module', 0, '2022-04-13 12:33:44', '2022-04-13 12:33:44', NULL),
    (11, 'Students', 'Manage all students related functionalities', 0, '2022-04-13 13:12:39', '2022-04-13 13:12:39', NULL),
    (12, 'Company', 'Manage company module functionalities', 0, '2022-04-13 13:15:11', '2022-04-13 13:15:11', NULL),
    (13, 'Branch', 'Manage branch functionalities', 0, '2022-04-13 13:15:48', '2022-04-13 13:15:48', NULL),
    (14, 'Class Groups', 'Manage class groups', 10, '2022-04-13 13:20:19', '2022-04-13 13:20:19', NULL),
    (15, 'System Settings', 'Basic system settings', 0, '2022-04-14 10:47:25', '2022-04-14 10:47:25', NULL),
    (16, 'Franchise Application', 'Manage franchise application module', 0, '2022-05-20 01:15:19', '2022-05-20 01:15:19', NULL),
    (17, 'Franchise Application BD', 'Manage franchise application BD module', 16, '2022-05-20 01:15:58', '2022-05-20 01:15:58', NULL),
    (18, 'Franchise Application QA', 'Manage franchise application QA module', 16, '2022-05-20 01:16:19', '2022-05-20 01:16:19', NULL),
    (19, 'Franchise Application TOR', 'Manage franchise application TOR module', 16, '2022-05-20 01:16:41', '2022-05-20 01:16:41', NULL),
    (20, 'Franchise Application Legal', 'Manage franchise application Legal Application module', 16, '2022-05-20 01:17:03', '2022-05-20 01:17:03', NULL),
    (21, 'Franchise Application DD', 'Manage franchise application Deputy Director module', 16, '2022-05-20 01:17:25', '2022-05-20 01:17:54', NULL),
    (22, 'Franchise Application Upload Document', 'Manage franchise application Upload Document module', 16, '2022-05-20 01:49:03', '2022-05-20 01:49:03', NULL);");
    }
}
