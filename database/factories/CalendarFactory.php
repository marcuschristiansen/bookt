<?php

namespace Database\Factories;

use App\Models\Calendar;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalendarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Calendar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'property_id' => Property::all()->random(),
            'name' => $this->faker->company,
        ];
    }
}
