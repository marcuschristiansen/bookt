<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Team;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $property = $this->faker->name() . ' Property';
        $hashIds = new Hashids($property . time(), 8);

        return [
            'team_id' => $team,
            'name' => $property,
            'joining_code' => Str::upper($hashIds->encode(1)),
            'is_private' => rand(0,1),
            'address' => $this->faker->address,
            'contact_number' => $this->faker->e164PhoneNumber,
            'description' => $this->faker->realText()
        ];
    }
}
