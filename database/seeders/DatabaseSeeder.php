<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Calendar;
use App\Models\Property;
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
     * Setup team for a user including properties and calendars
     *
     * @param User $user
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed
     * @throws \Exception
     */
    private function createTeam(User $user)
    {
        // Create a team for this team admin
        return tap(Team::factory()->create([
            'user_id' => $user->getKey(),
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
        ]), function(Team $team) {
            // Create 3 private properties for this team
            Property::factory(3)->create([
                'team_id' => $team,
                'name' => $team->name . ' ' . $this->faker->word . ' Property',
                'is_private' => true
            ])->each(function(Property $property) {
                Calendar::factory(3)->create(['property_id' => $property->getKey()
                ])->each(function(Calendar $calendar) {
                    // Create slots
                    $this->createSlots($calendar);
                });
            });

            // Create 3 public properties for this team
            Property::factory(3)->create([
                'team_id' => $team,
                'name' => $team->name . ' ' . $this->faker->word . ' Property',
                'is_private' => false
            ])->each(function(Property $property) {
                Calendar::factory(3)->create(['property_id' => $property->getKey()
                ])->each(function(Calendar $calendar) {
                    // Create slots
                    $this->createSlots($calendar);
                });
            });
        });
    }

    /**
     * @param Calendar $calendar
     * @return void
     * @throws \Exception
     */
    private function createSlots(Calendar $calendar): void
    {
        foreach (Slot::DAYS as $dayKey => $day) {
            foreach(self::TIME_SLOTS as $key => $timeSlot) {
                tap(Slot::factory()->create([
                    'calendar_id' => $calendar->getKey(),
                    'day_id' => $dayKey,
                    'start_time' => $timeSlot,
                    'end_time' => (array_key_exists(++$key, self::TIME_SLOTS)) ? self::TIME_SLOTS[$key++] : '18:00',
                    'max_bookings' => random_int(1, 15),
                    'cost' => random_int(0, 200),
                    'deleted_at' => null
                ]), function(Slot $slot) {});
            }
        }
    }

    /**
     * @param User $user
     * @param Property $property
     */
    private function createBookings(User $user, Property $property)
    {
        foreach (range(1,rand(2,15)) as $n) {
            $booking = Booking::factory()->create([
                'user_id' => $user->getKey(),
                'property_id' => $property->getKey(),
                'date' => $this->faker->dateTimeBetween('now', '+2 months')
            ]);
            $slot = $property->calendars->random()->slots->random()->getKey();
            $booking->slots()->sync([$slot]);
        }
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

        /**
         * SUPER ADMIN USERS
         */
        $superAdmin = User::factory()->create([
            'name' => 'Marcus Christiansen',
            'email' => env('EMAIL'),
            'password' => Hash::make(env('PASSWORD')),
        ]);
        $superAdmin->assignRole(Role::findByName('super-admin'));

        /**
         * TEAM ADMIN USERS
         */
        $teamAdmin = User::factory()->create([
            'name' => 'Meghnaaz Williams',
            'email' => 'meghnaaz.williams@gmail.com',
            'password' => Hash::make(env('PASSWORD')),
        ]);
        $teamAdmin->assignRole(Role::findByName('team-admin'));
        $team = $this->createTeam($teamAdmin);

        $teamAdminTwo = User::factory()->create([
            'name' => 'Denis Christiansen',
            'email' => 'denis.christiansen@gmail.com',
            'password' => Hash::make(env('PASSWORD')),
        ]);
        $teamAdminTwo->assignRole(Role::findByName('team-admin'));
        $this->createTeam($teamAdminTwo);

//        /**
//         * USERS
//         */
        $user = User::factory()->create([
            'name' => 'Pieter Mentz',
            'email' => 'pieter.mentz@gmail.com',
            'password' => Hash::make('password')
        ]);
        $this->createTeam($user);
        $user->assignRole(Role::findByName('user'));
        // Add user to properties
        $properties = Property::all()->random(3);
        $user->addToProperties($properties);
        // Create bookings
        $this->createBookings($user, $properties->random());


        // Create additional random users
        User::factory(50)->create()->each(function(User $user) {
            $this->createTeam($user);
            $user->assignRole(Role::findByName('user'));
            // Add user to properties
            $properties = Property::all()->random(3);
            $user->addToProperties($properties);
            // Create bookings
            $this->createBookings($user, $properties->random());
        });
    }
}
