<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(CollegeSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(InstitutionSeeder::class);
        $this->call(EmploymentTypeSeeder::class);
        $this->call(DecreeSeeder::class);
        $this->call(DisciplinarySeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(UserSeeder::class);
    }
}
