<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            LaratrustSeeder::class,
            AttendanceStatusSeeder::class,
            BankAccountSeeder::class,
            BranchClassSeeder::class,
            BranchSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            //TownSeeder::class,
            //ClassSeeder::class,
            CompanySeeder::class,
            ContactInformationSeeder::class,
            FranchiseApplicationAttachmentTypeSeeder::class,
            NetworkAssociateSeeder::class,
            NetworkAssociateBranchSeeder::class,
            RegionSeeder::class,
            //SectionSeeder::class,
            UserSeeder::class,
            SystemModuleSeeder::class,
            PermissionsSeeder::class,
            PermissionRoleSeeder::class,
            BuildingTypeSeeder::class,
            //ClassGroupSeeder::class,
            FeePackageTypeSeeder::class,
            LanguageSeeder::class,
            NationalitySeeder::class,
            ReligionSeeder::class,
            WeekSeeder::class,
            TermSeeder::class,
            ConstituencySeeder::class,
            SourceSeeder::class,
            RelationSeeder::class,
            InvoiceTypeSeeder::class,
            AttendanceTypeSeeder::class,
            IncomeTaxSlabSeeder::class
        ]);
    }
}
