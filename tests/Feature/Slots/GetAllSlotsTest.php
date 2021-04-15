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

class GetAllSlotsTest extends TestCase
{
    /**
     * Test that an unauthorised user cannot create properties
     */
    public function testUnauthorizedAccess()
    {
        $this->get('/calendars/1/slots');

        $this->assertGuest();
    }

    /**
     * Test that a team admin can view all the slots that belong to the property
     */
    public function testTeamAdminCanViewAllSlots()
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
        $calendars = Calendar::factory(2)->create(['property_id' => $property->getKey()]);
        $slots = Slot::factory(3)->create(['calendar_id' => $calendars->first()->getKey()]);

        $response = $this->actingAs($user)->get('/calendars/' . $calendars->first()->getKey() . '/slots');
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Slots/Index')
            ->has('slots.data', 3)
            ->has('slots.data.0', fn (Assert $page) =>
            $page
                ->where('type', 'slots')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('calendar_id', (string)$calendars->first()->getKey())
                    ->hasAll(['start_time', 'end_time', 'max_bookings', 'calendar', 'day_id', 'cost'])
                )
            )
        );
    }

    /**
     * Test that a team member can view all the calendars that belong to the property
     */
    public function testTeamMemberCanViewAllCalendars()
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
        $calendars = Calendar::factory(2)->create(['property_id' => $property->getKey()]);
        $slots = Slot::factory(3)->create(['calendar_id' => $calendars->first()->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $teams->first(), $userTwo->email, 'user');

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendars->first()->getKey() . '/slots');
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Slots/Index')
            ->has('slots.data', 3)
            ->has('slots.data.0', fn (Assert $page) =>
            $page
                ->where('type', 'slots')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('calendar_id', (string)$calendars->first()->getKey())
                    ->hasAll(['start_time', 'end_time', 'max_bookings', 'calendar', 'day_id', 'cost'])
                )
            )
        );
    }

    /**
     * Test that a user can view all calendars of a public propertys
     */
    public function testUserCanViewAllCalendarsOfAPublicProperty()
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
        $calendars = Calendar::factory(2)->create(['property_id' => $property->getKey()]);
        $slots = Slot::factory(3)->create(['calendar_id' => $calendars->first()->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendars->first()->getKey() . '/slots');
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Slots/Index')
            ->has('slots.data', 3)
            ->has('slots.data.0', fn (Assert $page) =>
            $page
                ->where('type', 'slots')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('calendar_id', (string)$calendars->first()->getKey())
                    ->hasAll(['start_time', 'end_time', 'max_bookings', 'calendar', 'day_id', 'cost'])
                )
            )
        );
    }

    /**
     * Test that a user can view all calendars of a private property they belong to
     */
    public function testUserCanViewAllCalendarsOfAPrivatePropertyTheyBelongTo()
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
        $calendars = Calendar::factory(2)->create(['property_id' => $property->getKey()]);
        $slots = Slot::factory(3)->create(['calendar_id' => $calendars->first()->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->addToProperty($property);

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendars->first()->getKey() . '/slots');
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Slots/Index')
            ->has('slots.data', 3)
            ->has('slots.data.0', fn (Assert $page) =>
            $page
                ->where('type', 'slots')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('calendar_id', (string)$calendars->first()->getKey())
                    ->hasAll(['start_time', 'end_time', 'max_bookings', 'calendar', 'day_id', 'cost'])
                )
            )
        );
    }

    /**
     * Test that a user cannot view the calendars of a private property
     */
    public function testUserCannotViewCalendarsOfAPrivateProperty()
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
        $calendars = Calendar::factory(2)->create(['property_id' => $property->getKey()]);
        $slots = Slot::factory(3)->create(['calendar_id' => $calendars->first()->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendars->first()->getKey() . '/slots');
        $response->assertStatus(403);
    }

    /**
     * Test that a team admin cannot view the calendars of properties they do not own
     */
    public function testTeamAdminCannotViewCalendarsOfPropertiesTheyDoNotOwn()
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
        $calendars = Calendar::factory(2)->create(['property_id' => $property->getKey()]);
        $slots = Slot::factory(3)->create(['calendar_id' => $calendars->first()->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));

        $response = $this->actingAs($userTwo)->get('/calendars/' . $calendars->first()->getKey() . '/slots');
        $response->assertStatus(403);
    }
}
