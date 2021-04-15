<?php

namespace Database\Factories;

use App\Models\Calendar;
use App\Models\Slot;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlotFactory extends Factory
{
    /**
     * array TIME_SLOTS
     */
    const TIME_SLOTS = [
        '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'
    ];

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startTime = collect(self::TIME_SLOTS)->random();
        $calendar = Calendar::all()->random();
        return [
            'calendar_id' => $calendar->getKey(),
            'start_time' => $startTime,
            'end_time' => date('H:i', strtotime($startTime) + 60 * 60),
            'max_bookings' => random_int(1, 15),
            'day_id' => random_int(1, 7),
            'cost' => '50.00'
        ];
    }
}
