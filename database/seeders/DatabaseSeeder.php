<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\Pass;
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
            // Create 2 properties for this team
            tap(Property::factory()->create([
                'team_id' => $team,
                'name' => $team->name . ' Property',
                'is_private' => true
            ]), function(Property $property) {
                // Create 3 calendars for each property
//                Calendar::factory(3)->create(['property_id' => $property->getKey()]);

            });
            tap(Property::factory()->create([
                'team_id' => $team,
                'name' => $team->name . ' Property Number 2',
                'is_private' => false
            ]), function(Property $property) {
                // Create 3 calendars for each property
                Calendar::factory(3)->create(['property_id' => $property->getKey()]);
            });
            // Create slots
//            $this->createSlots($team);
        });
    }

    /**
     * @param Team $team
     * @return void
     * @throws \Exception
     */
    private function createSlots(Team $team): void
    {
        foreach(self::TIME_SLOTS as $key => $timeSlot) {
            tap(Slot::factory()->create([
                'team_id' => $team,
                'start_time' => $timeSlot,
                'end_time' => (array_key_exists(++$key, self::TIME_SLOTS)) ? self::TIME_SLOTS[$key++] : '18:00',
                'max_bookings' => random_int(1, 15)
            ]), function(Slot $slot) use ($team) {
                // Get the default pass created for this slot
                $defaultPass = Pass::create(['name' => $slot->id . ' Default Pass', 'team_id' => $slot->team->getKey()]);
//                $customDate = rand(0,1);
                // Create a pass for either 3 days in the week or 2 custom dates
//                $passRange = PassRange::create([
//                    'days' => (!$customDate) ? collect(Pass::DAYS)->random(3)->toArray() : null,
//                    'dates' => ($customDate) ? [$this->faker->dateTimeBetween('now', '+2 months')->format('Y-m-d'), $this->faker->dateTimeBetween('now', '+2 months')->format('Y-m-d')] : null,
//                ]);
                // Create relationships
//                $defaultPass->synchronisePassRanges([$passRange->getKey()]);

                $defaultPass->slots()->sync($slot->getKey());

                $calendar = $team->properties->random()->calendars->random();
                $defaultPass->calendars()->sync($calendar->getKey());
            });
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
//        $user = User::factory()->create([
//            'name' => 'Pieter Mentz',
//            'email' => 'pieter.mentz@gmail.com',
//            'password' => Hash::make('password')
//        ]);
//        // Create personal team
//        Team::create([
//            'user_id' => $user->getKey(),
//            'name' => explode(' ', $user->name, 2)[0]."'s Team",
//            'personal_team' => true,
//        ]);
//        // Assign user role
//        $user->assignRole(Role::findByName('user'));
//        // Add user to 2 properties
//        $properties = Property::all()->random(2);
//        $user->addToProperties($properties);
        // Create between 1-15 bookings
//        foreach (range(1,rand(2,15)) as $n) {
//            $property = $properties->random();
//            $pass = $property->team->passes->random();
//            Booking::factory()->create([
//                'user_id' => $user,
//                'property_id' => $property,
//                'pass_id' => $pass,
//                'date' => $this->faker->dateTimeBetween('now', '+2 months')
//            ]);
//        }

//        // Create additional 50 users
//        User::factory(50)->create()->each(function(User $user) {
//            // Create personal team
//            Team::create([
//                'user_id' => $user->getKey(),
//                'name' => explode(' ', $user->name, 2)[0]."'s Team",
//                'personal_team' => true,
//            ]);
//            // Assign user role
//            $user->assignRole(Role::findByName('user'));
//            // Add user to 2 properties
//            $properties = Property::all()->random(2);
//            $user->addToProperties($properties);
//            // Create between 1-15 bookings
////            foreach (range(1,15) as $n) {
////                $property = $properties->random();
////                $pass = $property->team->passes->random();
////                Booking::factory()->create([
////                    'user_id' => $user,
////                    'property_id' => $property,
////                    'pass_id' => $pass,
////                    'date' => $this->faker->dateTimeBetween('now', '+2 months')
////                ]);
////            }
//        });
    }
}
