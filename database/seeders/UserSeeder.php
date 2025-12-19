<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Acadmiqo',
                'email' => 'superadmin@academiqo.com',
                'CNIC' => '57135-7158963-8',
                'gender' => 'Male',
                'date_of_birth' => '2022-02-22 05:14:32',
                'password' => bcrypt('Academiqo@123')
            ],
        ];

        // Create or update User
        $user = User::updateOrCreate(
            ['email' => 'superadmin@academiqo.com'],
            $users[0]
        );

        // Ensure user has super_admin role
        if (! $user->hasRole('super_admin')) {
            $user->addRole('super_admin'); // Updated for Laratrust v8
        }

        // Create Employee Profile for Super Admin if doesn't exist
        if (! $user->employee) {
            Employee::create([
                'user_id' => $user->id,
                'employee_id' => 'SA-001',
                'prefix' => 'Mr.',
                'preferred_name' => 'Super Admin',
                'date_of_birth' => '2022-02-22',
                'job_status' => 'active',
                'hiring_date' => now(),
                'country_id' => 1, // Default country
                'state_id' => 1,   // Default state
                'city_id' => 1,    // Default city
            ]);
        }
    }
}
