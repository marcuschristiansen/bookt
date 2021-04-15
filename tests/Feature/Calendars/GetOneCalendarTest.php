<?php

namespace Tests\Feature\Calendars;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Calendar;
use App\Models\Property;
use App\Models\User;
use Inertia\Testing\Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetOneCalendarTest extends TestCase
{
    /**
     * Test that an unauthorised user cannot fetch any calendars
     */
    public function testUnauthorizedAccess()
    {
        $this->get('/properties/1/calendars/1');

        $this->assertGuest();
    }

    /**
     * Test that a team admin can view a calendar through a property they own
     */
    public function testTeamAdminCanViewCalendarTheyOwn()
    {
        // Create a user with 2 teams and 3 properties for each team
        $user = User::factory()->withPersonalTeam()->create();
        $user->assignRole(Role::findByName('user'));
        $user->assignRole(Role::findByName('team-admin'));
        $teams = $user->ownedTeams;

        $properties = Property::factory(2)->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ])->each(fn (Property $property) =>
            Calendar::factory(3)->create(['property_id' => $property->getKey()]
        ));

        $calendar = $properties->first()->calendars->first();

        $response = $this->actingAs($user)->get('/properties/' . $properties->first()->getKey() . '/calendars/' . $calendar->getKey());
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Calendars/Show')
            ->has('calendar.data', fn (Assert $page) =>
            $page
                ->where('type', 'calendars')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('property_id', (string)$properties->first()->getKey())
                    ->hasAll(['property', 'name'])
                )
            )
        );
    }

    /**
     * Test that a team member can view a calendar whose property they are a team member of
     */
    public function testTeamMemberCanViewCalendarOfATeamTheyArePartOf()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $properties = Property::factory(2)->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ])->each(fn (Property $property) =>
        Calendar::factory(3)->create(['property_id' => $property->getKey()]
        ));

        $calendar = $properties->first()->calendars->first();

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $teams->first(), $userTwo->email, 'user');

        $response = $this->actingAs($userTwo)->get('/properties/' . $properties->first()->getKey() . '/calendars/' . $calendar->getKey());
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Calendars/Show')
            ->has('calendar.data', fn (Assert $page) =>
            $page
                ->where('type', 'calendars')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('property_id', (string)$properties->first()->getKey())
                    ->hasAll(['property', 'name'])
                )
            )
        );
    }

    /**
     * Test that a user can view a calendar whose private property they are part of
     */
    public function testUserCanViewAPrivateCalendarWhosePropertyTheyAreAMemberOf()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $properties = Property::factory(2)->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ])->each(fn (Property $property) =>
        Calendar::factory(3)->create(['property_id' => $property->getKey()]
        ));

        $calendar = $properties->first()->calendars->first();

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->addToProperty($properties->first());

        $response = $this->actingAs($userTwo)->get('/properties/' . $properties->first()->getKey() . '/calendars/' . $calendar->getKey());
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Calendars/Show')
            ->has('calendar.data', fn (Assert $page) =>
            $page
                ->where('type', 'calendars')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('property_id', (string)$properties->first()->getKey())
                    ->hasAll(['property', 'name'])
                )
            )
        );
    }

    /**
     * Test that a user can view a calendar whose property is public
     */
    public function testUserCanViewAPublicCalendar()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $properties = Property::factory(2)->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => false
        ])->each(fn (Property $property) =>
        Calendar::factory(3)->create(['property_id' => $property->getKey()]
        ));

        $calendar = $properties->first()->calendars->first();

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->get('/properties/' . $properties->first()->getKey() . '/calendars/' . $calendar->getKey());
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
        $page
            ->component('Calendars/Show')
            ->has('calendar.data', fn (Assert $page) =>
            $page
                ->where('type', 'calendars')
                ->has('id')
                ->has('links')
                ->has('attributes', fn (Assert $page) =>
                $page
                    ->where('property_id', (string)$properties->first()->getKey())
                    ->hasAll(['property', 'name'])
                )
            )
        );
    }

    /**
     * Test that a user cannot view a calendar whose property is private
     */
    public function testUserCannotViewAPrivateCalendar()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $properties = Property::factory(2)->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ])->each(fn (Property $property) =>
        Calendar::factory(3)->create(['property_id' => $property->getKey()]
        ));

        $calendar = $properties->first()->calendars->first();

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->get('/properties/' . $properties->first()->getKey() . '/calendars/' . $calendar->getKey());
        $response->assertStatus(403);
    }

    /**
     * Test that a team admin cannot view a calendar whose property they do not own
     */
    public function testTeamAdminCannotViewACalendarTheyDoNotOwn()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $properties = Property::factory(2)->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ])->each(fn (Property $property) =>
        Calendar::factory(3)->create(['property_id' => $property->getKey()]
        ));

        $calendar = $properties->first()->calendars->first();

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));

        $response = $this->actingAs($userTwo)->get('/properties/' . $properties->first()->getKey() . '/calendars/' . $calendar->getKey());
        $response->assertStatus(403);
    }

    /**
     * Test that a user cannot view a calendar that does not belong to a property they own
     */
    public function testTeamAdminCannotViewACalendarThatDoesNotBelongToTheProperty()
    {
        // Create a user with property and calendar
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $propertyOne = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendarOne = Calendar::factory()->create(['property_id' => $propertyOne->getKey()]);

        // Create a user with property and calendar
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teams = $userTwo->ownedTeams;

        $propertyTwo = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendarTwo = Calendar::factory()->create(['property_id' => $propertyTwo->getKey()]);

        // Try updating a calendar which belongs to another user through a property that DOES belong to the user
        $response = $this->actingAs($userOne)->get('/properties/' . $propertyOne->getKey(). '/calendars/' . $calendarTwo->getKey());
        $response->assertStatus(404);
    }
}
