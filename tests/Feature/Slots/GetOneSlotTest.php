<?php

namespace Tests\Feature\Slots;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Calendar;
use App\Models\Property;
use App\Models\Slot;
use App\Models\User;
use Inertia\Testing\Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetOneSlotTest extends TestCase
{
    /**
     * Test that an unauthorised user cannot fetch any calendars
     */
    public function testUnauthorizedAccess()
    {
        $this->get('/calendars/1/slots/1');

        $this->assertGuest();
    }

    /**
     * Test that a team admin can view a slot through a calendar they own
     */
    public function testTeamAdminCanViewSlotTheyOwn()
    {
        // Create a user with 2 teams and 3 properties for each team
        $user = User::factory()->withPersonalTeam()->create();
        $user->assignRole(Role::findByName('user'));
        $user->assignRole(Role::findByName('team-admin'));
        $teams = $user->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendar = Calendar::factory()->create(['property_id' => $property->getKey()]);
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        $response = $this->actingAs($user)->get('/calendars/' . $calendar->getKey() . '/slots/' . $slot->getKey());
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Slots/Show')
            ->has('slot.data', fn (Assert $page) =>
            $page
                ->where('type', 'slots')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('calendar_id', (string)$calendar->getKey())
                    ->hasAll(['start_time', 'end_time', 'max_bookings', 'calendar', 'day_id', 'cost'])
                )
            )
        );
    }

    /**
     * Test that a team member can view a calendar whose property they are a team member of
     */
    public function testTeamMemberCanViewSlotOfATeamTheyArePartOf()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendar = Calendar::factory()->create(['property_id' => $property->getKey()]);
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $teams->first(), $userTwo->email, 'user');

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendar->getKey() . '/slots/' . $slot->getKey());
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Slots/Show')
            ->has('slot.data', fn (Assert $page) =>
            $page
                ->where('type', 'slots')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('calendar_id', (string)$calendar->getKey())
                    ->hasAll(['start_time', 'end_time', 'max_bookings', 'calendar', 'day_id', 'cost'])
                )
            )
        );
    }

    /**
     * Test that a user can view a slot whose private property they are part of
     */
    public function testUserCanViewAPrivateSlotWhosePropertyTheyAreAMemberOf()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendar = Calendar::factory()->create(['property_id' => $property->getKey()]);
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->addToProperty($property);

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendar->getKey() . '/slots/' . $slot->getKey());
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Slots/Show')
            ->has('slot.data', fn (Assert $page) =>
            $page
                ->where('type', 'slots')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('calendar_id', (string)$calendar->getKey())
                    ->hasAll(['start_time', 'end_time', 'max_bookings', 'calendar', 'day_id', 'cost'])
                )
            )
        );
    }

    /**
     * Test that a user can view a slot whose property is public
     */
    public function testUserCanViewAPublicSlot()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => false
        ]);
        $calendar = Calendar::factory()->create(['property_id' => $property->getKey()]);
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendar->getKey() . '/slots/' . $slot->getKey());
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Slots/Show')
            ->has('slot.data', fn (Assert $page) =>
            $page
                ->where('type', 'slots')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('calendar_id', (string)$calendar->getKey())
                    ->hasAll(['start_time', 'end_time', 'max_bookings', 'calendar', 'day_id', 'cost'])
                )
            )
        );
    }

    /**
     * Test that a user cannot view a slot whose property is private
     */
    public function testUserCannotViewAPrivateSlot()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendar = Calendar::factory()->create(['property_id' => $property->getKey()]);
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendar->getKey() . '/slots/' . $slot->getKey());
        $response->assertStatus(403);
    }

    /**
     * Test that a team admin cannot view a slot whose property they do not own
     */
    public function testTeamAdminCannotViewASlotTheyDoNotOwn()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendar = Calendar::factory()->create(['property_id' => $property->getKey()]);
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendar->getKey() . '/slots/' . $slot->getKey());
        $response->assertStatus(403);
    }

    /**
     * Test that a user cannot view a calendar that does not belong to a property they own
     */
    public function testTeamAdminCannotViewASlotThatDoesNotBelongToTheProperty()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $propertyOne = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendarOne = Calendar::factory()->create(['property_id' => $propertyOne->getKey()]);
        $slotOne = Slot::factory()->create(['calendar_id' => $calendarOne->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teams = $userTwo->ownedTeams;

        $propertyTwo = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendarTwo = Calendar::factory()->create(['property_id' => $propertyTwo->getKey()]);
        $slotTwo = Slot::factory()->create(['calendar_id' => $calendarTwo->getKey()]);

        $response = $this->actingAs($userOne)->get('/calendars/' . $calendarOne->getKey() . '/slots/' . $slotTwo->getKey());
        $response->assertStatus(404);
    }
}
