<?php

namespace Tests\Feature\Slots;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Calendar;
use App\Models\Property;
use App\Models\Slot;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteSlotsTest extends TestCase
{
    /**
     * Test that an unauthorised user cannot delete calendars
     */
    public function testUnauthorizedAccess()
    {
        $this->delete('/calendars/1/slots/1');

        $this->assertGuest();
    }

    /**
     * Test that a team admin can delete their own slots
     */
    public function testTeamAdminCanDeleteSlots()
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

        $response = $this->actingAs($user)->delete('/calendars/' . $calendar->getKey(). '/slots/' . $slot->getKey());
        $response->assertStatus(302);
        $this->assertSoftDeleted('slots', ['id' => $slot->getKey()]);
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
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $teams->first(), $userTwo->email, 'user');

        $response = $this->actingAs($userTwo)->delete('/calendars/' . $calendar->getKey(). '/slots/' . $slot->getKey());
        $response->assertStatus(302);
        $this->assertSoftDeleted('slots', ['id' => $slot->getKey()]);
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
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->delete('/calendars/' . $calendar->getKey(). '/slots/' . $slot->getKey());
        $response->assertStatus(403);
        $this->assertDatabaseHas('slots', ['id' => $slot->getKey(), 'deleted_at' => null]);
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
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));

        $response = $this->actingAs($userTwo)->delete('/calendars/' . $calendar->getKey(). '/slots/' . $slot->getKey());
        $response->assertStatus(403);
        $this->assertDatabaseHas('slots', ['id' => $slot->getKey(), 'deleted_at' => null]);
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
        $slotOne = Slot::factory()->create(['calendar_id' => $calendarOne->getKey()]);

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
        $slotTwo = Slot::factory()->create(['calendar_id' => $calendarTwo->getKey()]);

        // Try updating a slot which belongs to another user through a calendar that DOES belong to the user
        $response = $this->actingAs($userOne)->delete('/calendars/' . $calendarOne->getKey(). '/slots/' . $slotTwo->getKey());
        $response->assertStatus(404);
    }
}
