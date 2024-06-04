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
        $this->call(ResidenceSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(EchelonSeeder::class);
        $this->call(InstitutionSeeder::class);
        $this->call(EmploymentTypeSeeder::class);
        $this->call(DecreeSeeder::class);
        $this->call(DisciplinarySeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(UserSeeder::class);

        // Migrate Old Data to New Database
        $this->call(OldUserSeeder::class);
        $this->call(OldEducationSeeder::class);
        $this->call(OldPositionSeeder::class);
        $this->call(OldGradeSeeder::class);
        // $this->call(OldTrainingSeeder::class);
        $this->call(OldRecognitionSeeder::class);
        $this->call(OldPerformanceSeeder::class);
        $this->call(OldDisciplinarySeeder::class);
        $this->call(OldFamilySeeder::class);
        $this->call(OldLeaveSeeder::class);
    }
}
