<?php

namespace Database\Factories;

use App\Models\Calendar;
use App\Models\Team;
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
            'team_id' => Team::all()->random(),
            'name' => $this->faker->company,
        ];
    }
}
