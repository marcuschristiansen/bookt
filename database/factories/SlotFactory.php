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
        $startTime = array_rand(self::TIME_SLOTS);
        $endTime = $startTime++;
        return [
            'calendar_id' => Calendar::all()->random(),
            'day_id' => range(1, 7),
//            'start_time' => self::TIME_SLOTS[$startTime],
//            'end_time' => (array_key_exists($endTime, self::TIME_SLOTS)) ? self::TIME_SLOTS[$endTime] : '18:00',
            'max_bookings' => random_int(1, 15)
        ];
    }
}
