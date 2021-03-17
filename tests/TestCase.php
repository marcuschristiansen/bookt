<?php

namespace Tests;

use App\Models\Booking;
use App\Models\Calendar;
use App\Models\Slot;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Faker\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;
//    use MigrateFreshSeedOnce;

    /**
     * @var User $teamAdminOne
     */
    public $teamAdminOne;

    /**
     * @var User $teamAdminTwo
     */
    public $teamAdminTwo;

    /**
     * @var User $userOne
     */
    public $userOne;

    /**
     * @var User $userTwo
     */
    public $userTwo;

    /**
     * @var User $userThree
     */
    public $userThree;

    /**
     * array TIME_SLOTS
     */
    const TIME_SLOTS = [
        '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'
    ];

    /**
     * TestCase constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
     * Set up test data for each test class
     *
     * @throws \Exception
     */
    protected function setUpTestData()
    {
        // Roles and permissions
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

        // Create 2 team admins
        $this->teamAdminOne = User::factory()->create();
        $this->teamAdminOne->assignRole(Role::findByName('team-admin'));
        $this->teamAdminTwo = User::factory()->create();
        $this->teamAdminTwo->assignRole(Role::findByName('team-admin'));

        // Create 1 team for each team admin
        $teamOne = Team::factory()->create(['user_id' => $this->teamAdminOne->getKey()]);
        $teamOne->calendars()->create([
            'team_id' => $teamOne->getKey(),
            'name' => $this->faker->company
        ]);
        $teamOne->calendars->each(function($calendar) {
            $this->createSlots($calendar);
        });
        $teamTwo = Team::factory()->create(['user_id' => $this->teamAdminTwo->getKey()]);
        $teamTwo->calendars()->create([
            'team_id' => $teamTwo->getKey(),
            'name' => $this->faker->company
        ]);
        $teamTwo->calendars->each(function($calendar) {
            $this->createSlots($calendar);
        });

        // Create 1 user for each team
        $this->userOne = User::factory()->create();
        $this->userOne->assignRole(Role::findByName('user'));
        $this->userOne->teams()->sync([$teamOne->getKey() => ['role' => 'user']]);

        $this->userTwo = User::factory()->create();
        $this->userTwo->assignRole(Role::findByName('user'));
        $this->userTwo->teams()->sync([$teamTwo->getKey() => ['role' => 'user']]);

        // UserThree does not belong to any teams
        $this->userThree = User::factory()->create();
        $this->userThree->assignRole(Role::findByName('user'));

        // Create bookings for each user
        Booking::factory(10)->create([
            'user_id' => $this->userOne,
            'team_id' => $teamOne,
            'slot_id' => $teamOne->calendars->first()->slots->random(),
            'date' => $this->faker->dateTimeBetween('now', '+1 years')
        ]);
        Booking::factory(10)->create([
            'user_id' => $this->userTwo,
            'team_id' => $teamTwo,
            'slot_id' => $teamTwo->calendars->first()->slots->random(),
            'date' => $this->faker->dateTimeBetween('now', '+1 years')
        ]);
    }
}
