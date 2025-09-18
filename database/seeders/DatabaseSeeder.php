<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            FacultySeeder::class,
        ]);

        $this->call([
            FeeGroupSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'Admin@gmail.com',
            'password' => Hash::make('admin123!'),
        ])->assignRole(Role::create([
            'name' => 'Admin'
        ]));

        
        $operator = User::factory()->create([
            'name' => 'Riski',
            'email' => 'Riski@gmail.com',
            'password' => Hash::make('operator123!'),
        ])->assignRole(Role::create([
            'name' => 'Operator'
        ]));

        $operator->operator()->create([
            'faculty_id' => 1,
            'departement_id' => 1,
            'employee_number' => str()->padLeft(mt_rand(0, 999999), 6, '0')
        ]);

        $teacher = User::factory()->create([
            'name' => 'Budi',
            'email' => 'Budi@gmail.com',
            'password' => Hash::make('teacher123!'),
        ])->assignRole(Role::create([
            'name' => 'Teacher'
        ]));

        $teacher->teacher()->create([
            'faculty_id' => 1,
            'departement_id' => 1,
            'teacher_number' => str()->padLeft(mt_rand(0, 999999), 6, '0'),
            'academic_title' => 'Asisten Ahli'
        ]);

        $student = User::factory()->create([
            'name' => 'Alif',
            'email' => 'Alif@gmail.com',
            'password' => Hash::make('student123!'),
        ])->assignRole(Role::create([
            'name' => 'Student'
        ]));

        $student->student()->create([
            'faculty_id' => 1,
            'departement_id' => 1,
            'fee_group_id' => rand(1, 6),
            'student_number' => str()->padLeft(mt_rand(0, 999999), 6, '0'),
            'semester' => 1,
            'batch' => 2025
        ]);
    }
}
