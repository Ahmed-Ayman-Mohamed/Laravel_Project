<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // Create 10 doctors
        Doctor::factory(10)->create()->each(function ($doctor) {
            // Get 2 or 3 random specialization IDs
            $specializations = Specialization::inRandomOrder()->take(rand(2, 3))->pluck('id');

            // Attach the random specializations to the doctor
            $doctor->specializations()->attach($specializations);
        });


        // $specializations = Specialization::all();

        // Doctor::factory()
        //     ->count(10)
        //     ->hasAttached($specializations->random(rand(1, 3)))
        //     ->create();
    }
}
