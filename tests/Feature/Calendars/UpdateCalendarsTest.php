<?php


namespace Tests\Feature\Calendars;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Calendar;
use App\Models\Property;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateCalendarsTest extends TestCase
{
    /**
     * @var array $formUpdates
     */
    public array $formUpdates = [];

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->formUpdates = [
            'name' => 'New updated calendar name',
        ];
    }

    /**
     * Test that an unauthorised user cannot create properties
     */
    public function testUnauthorizedAccess()
    {
        $this->post('/properties/1/calendars/1', $this->formUpdates);

        $this->assertGuest();
    }

    /**
     * Test that a team admin can update their own calendars
     */
    public function testTeamAdminCanUpdateOwnCalendar()
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

        $response = $this->actingAs($user)->put('/properties/' . $property->getKey(). '/calendars/' . $calendar->getKey(), $this->formUpdates);
        $response->assertStatus(302);
        $this->assertDatabaseHas('calendars', array_merge($this->formUpdates, ['id' => $calendar->getKey()]));
    }

    /**
     * Test that a team member can update the calendars of properties whose teams they are part of
     */
    public function testTeamMemberCanUpdateCalendarsForPropertiesTheyArePartOf()
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

        $response = $this->actingAs($userTwo)->put('/properties/' . $property->getKey(). '/calendars/' . $calendar->getKey(), $this->formUpdates);
        $response->assertStatus(302);
        $this->assertDatabaseHas('calendars', array_merge($this->formUpdates, ['id' => $calendar->getKey()]));
    }

    /**
     * Test that users cannot update any calendars
     */
    public function testUserCannotUpdateCalendars()
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

        $response = $this->actingAs($userTwo)->put('/properties/' . $property->getKey(). '/calendars/' . $calendar->getKey(), $this->formUpdates);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('calendars', array_merge($this->formUpdates, ['id' => $calendar->getKey()]));
    }

    /**
     * Test that a team admin cannot update a calendar whose property they do not own
     */
    public function testTeamAdminCannotUpdateCalendarsForPropertiesTheyDoNotOwn()
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

        $response = $this->actingAs($userTwo)->put('/properties/' . $property->getKey(). '/calendars/' . $calendar->getKey(), $this->formUpdates);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('calendars', array_merge($this->formUpdates, ['id' => $calendar->getKey()]));
    }

    /**
     * Test that a user cannot update a calendar that does not belong to a property they own
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
        $response = $this->actingAs($userOne)->put('/properties/' . $propertyOne->getKey(). '/calendars/' . $calendarTwo->getKey(), $this->formUpdates);
        $response->assertStatus(404);
        $this->assertDatabaseMissing('calendars', array_merge($this->formUpdates, ['id' => $calendarTwo->getKey()]));
    }
}
