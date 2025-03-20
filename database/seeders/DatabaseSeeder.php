<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Post::create([
        //     'title' => 'ahmed',
        //     'body' => 'Hello World'
        // ]);

        $this->call([
            SpecializationSeeder::class,  // Seed specializations first
            DoctorSeeder::class,
            PatientSeeder::class,
            ReviewSeeder::class,        // Seed doctors after
        ]);
    }
}
