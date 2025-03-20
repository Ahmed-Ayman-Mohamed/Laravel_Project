<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();


        $doctors->each(function ($doctor) use ($patients) {
            $patients->each(function ($patient) use ($doctor) {
                Review::factory()
                    ->count(1) // Generate 3 reviews for each patient-doctor pair
                    ->for($patient) // Associate the review with the current patient
                    ->for($doctor)  // Associate the review with the current doctor
                    ->create();
            });
        });
    }
}
