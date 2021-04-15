<?php

namespace Tests\Feature\Calendars;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Calendar;
use App\Models\Property;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteCalendarsTest extends TestCase
{
    /**
     * Test that an unauthorised user cannot delete calendars
     */
    public function testUnauthorizedAccess()
    {
        $this->delete('/properties/1/calendars/1');

        $this->assertGuest();
    }

    /**
     * Test that a team admin can delete their own calendars
     */
    public function testTeamAdminCanDeleteCalendars()
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

        $response = $this->actingAs($user)->delete('/properties/' . $property->getKey(). '/calendars/' . $calendar->getKey());
        $response->assertStatus(302);
        $this->assertSoftDeleted('calendars', ['id' => $calendar->getKey()]);
    }

    public function testTeamMemberCanDeleteCalendarsOfPropertiesWhoseTeamsTheyBelongTo()
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

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $teams->first(), $userTwo->email, 'user');

        $response = $this->actingAs($userTwo)->delete('/properties/' . $property->getKey(). '/calendars/' . $calendar->getKey());
        $response->assertStatus(302);
        $this->assertSoftDeleted('calendars', ['id' => $calendar->getKey()]);
    }

    public function testUserCannotDeleteCalendars()
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

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->delete('/properties/' . $property->getKey(). '/calendars/' . $calendar->getKey());
        $response->assertStatus(403);
        $this->assertDatabaseHas('calendars', ['id' => $calendar->getKey(), 'deleted_at' => null]);
    }

    public function testTeamAdminCannotDeleteCalendarsOfPropertiesTheyDoNotOwn()
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

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));

        $response = $this->actingAs($userTwo)->delete('/properties/' . $property->getKey(). '/calendars/' . $calendar->getKey());
        $response->assertStatus(403);
        $this->assertDatabaseHas('calendars', ['id' => $calendar->getKey(), 'deleted_at' => null]);
    }

    /**
     * Test that a user cannot delete a calendar that does not belong to a property they own
     */
    public function testTeamAdminCannotUpdateACalendarThatDoesNotBelongToTheProperty()
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
        $response = $this->actingAs($userOne)->delete('/properties/' . $propertyOne->getKey(). '/calendars/' . $calendarTwo->getKey());
        $response->assertStatus(404);
    }
}
