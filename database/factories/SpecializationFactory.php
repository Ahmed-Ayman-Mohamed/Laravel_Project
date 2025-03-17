<?php

namespace Database\Factories;

use App\Models\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialization>
 */
class SpecializationFactory extends Factory
{
    protected $model = Specialization::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,  // Generate a random word for the specialization name
        ];
    }
}
