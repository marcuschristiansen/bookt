<?php

namespace Database\Seeders;

use App\Http\Resources\Slot\SlotCollection;
use App\Models\Booking;
use App\Models\Calendar;
use App\Models\Slot;
use App\Models\Team;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * @var Factory $faker
     */
    private $faker;

    /**
     * array TIME_SLOTS
     */
    const TIME_SLOTS = [
        '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'
    ];

    /**
     * UserSeeder constructor.
     * @param Factory $faker
     */
    public function __construct(Factory $faker)
    {
        $this->faker = $faker->create();
    }

    /**
     * @param Calendar $calendar
     * @return array
     * @throws \Exception
     */
    private function createSlots(Calendar $calendar): array
    {
        $calendars = [];
        foreach (range(1, 7) as $day) {
            foreach(self::TIME_SLOTS as $key => $timeSlot) {
                $calendars[] = Slot::factory()->create([
                    'calendar_id' => $calendar,
                    'day_id' => $day,
                    'start_time' => $timeSlot,
                    'end_time' => (array_key_exists(++$key, self::TIME_SLOTS)) ? self::TIME_SLOTS[$key++] : '18:00',
                    'max_bookings' => random_int(1, 15)
                ]);
            }
        }

        return $calendars;
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Roles and permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Super Admin
        $superAdmin = User::factory()->create([
            'name' => 'Marcus Christiansen',
            'email' => env('EMAIL'),
            'password' => Hash::make(env('PASSWORD')),
        ]);
        $superAdmin->assignRole(Role::findByName('super-admin'));
        Team::factory()->create([
            'user_id' => $superAdmin->getKey()
        ]);

        // Team Admin
        $teamAdmin = User::factory()->create([
            'name' => 'Meghnaaz Williams',
            'email' => 'meghnaaz.williams@gmail.com',
            'password' => Hash::make(env('PASSWORD')),
        ]);
        $teamAdmin->assignRole(Role::findByName('team-admin'));
        $teams = Team::factory(3)->create([
            'user_id' => $teamAdmin->getKey()
        ]);

        // Calendars
        Calendar::factory(20)->create()->each(function($calendar) {
            $this->createSlots($calendar);
        });

        // Users
        $user = User::factory()->create([
            'name' => 'Pieter Mentz',
            'email' => 'pieter.mentz@gmail.com',
            'password' => Hash::make('password')
        ]);
        $user->assignRole(Role::findByName('user'));
        Team::factory()->create([
            'user_id' => $user
        ]);
        $team = $teams->random();
        $user->teams()->sync([$team->getKey() => ['role' => 'user']]);
        $calendar = $team->calendars->random();
        $slot = $calendar->slots->random();
        Booking::factory(50)->create([
            'user_id' => $user,
            'team_id' => $team,
            'slot_id' => $slot,
            'date' => $this->faker->dateTimeBetween('now', '+1 years')
        ]);

        $users = User::factory(10)->create();
        $users->each(function($user) use ($teams) {
            $user->assignRole(Role::findByName('user'));

            Team::factory()->create([
                'user_id' => $user
            ]);

            $team = $teams->random();
            $user->teams()->sync([$team->getKey() => ['role' => 'user']]);

            $calendar = $team->calendars->random();
            $slot = $calendar->slots->random();
            Booking::factory(10)->create([
                'user_id' => $user,
                'team_id' => $team,
                'slot_id' => $slot,
                'date' => $this->faker->dateTimeBetween('now', '+1 years')
            ]);
        });

    }
}
