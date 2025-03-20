<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    protected $model = Doctor::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'specialization' => $this->faker->word,  // Generate a random word as specialization
            'degree' => $this->faker->word,  // Generate a random word as degree
            'university' => $this->faker->company,  // Random university name
            'year_graduated' => $this->faker->year,  // Random graduation year
            'location' => $this->faker->city,  // Random city name as location
            'user_id' => User::factory()->create([
                'role' => 'doctor'
            ]),  // Associate a user to the doctor using the User factory

            'price' => $this->faker->numberBetween(100, 500),




            // 'user_role' => function(array $attributes){
            //     User::find($attributes['user_id'])->role;
            // }
        ];
    }
}
