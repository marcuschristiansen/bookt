<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $team = Team::all()->random();

        return [
            'team_id' => $team,
            'name' => $this->faker->name() . ' Property',
            'is_private' => rand(0,1),
            'address' => $this->faker->address,
            'contact_number' => $this->faker->e164PhoneNumber,
            'description' => $this->faker->realText()
        ];
    }
}
