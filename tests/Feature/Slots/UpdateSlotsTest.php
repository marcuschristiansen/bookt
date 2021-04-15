<?php


namespace Tests\Feature\Slots;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Calendar;
use App\Models\Property;
use App\Models\Slot;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateSlotsTest extends TestCase
{
    /**
     * @var array $formUpdates
     */
    public array $formData = [];

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->formData = [
            'start_time' => '11:00',
            'end_time' => '12:30',
            'day_id' => 3,
            'cost' => '100.00'
        ];
    }

    /**
     * Test that an unauthorised user cannot create properties
     */
    public function testUnauthorizedAccess()
    {
        $this->post('/calendars/1/slots/1', $this->formData);

        $this->assertGuest();
    }

    /**
     * Test that a team admin can update their own slots
     */
    public function testTeamAdminCanUpdateOwnSlot()
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

        $response = $this->actingAs($user)->put('/calendars/' . $calendar->getKey(). '/slots/' . $slot->getKey(), $this->formData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('slots', array_merge($this->formData, ['id' => $slot->getKey()]));
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
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $teams->first(), $userTwo->email, 'user');

        $response = $this->actingAs($userTwo)->put('/calendars/' . $calendar->getKey(). '/slots/' . $slot->getKey(), $this->formData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('slots', array_merge($this->formData, ['id' => $slot->getKey()]));
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
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->addToProperty($property);

        $response = $this->actingAs($userTwo)->put('/calendars/' . $calendar->getKey(). '/slots/' . $slot->getKey(), $this->formData);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('slots', array_merge($this->formData, ['id' => $slot->getKey()]));
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
        $slot = Slot::factory()->create(['calendar_id' => $calendar->getKey()]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));

        $response = $this->actingAs($userTwo)->put('/calendars/' . $calendar->getKey(). '/slots/' . $slot->getKey(), $this->formData);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('slots', array_merge($this->formData, ['id' => $slot->getKey()]));
    }

    /**
     * Test that a user cannot update a calendar that does not belong to a property they own
     */
    public function testTeamAdminCannotUpdateACalendarThatDoesNotBelongToTheProperty()
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
        $teams = $userOne->ownedTeams;

        $propertyTwo = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        $calendarTwo = Calendar::factory()->create(['property_id' => $propertyTwo->getKey()]);
        $slotTwo = Slot::factory()->create(['calendar_id' => $calendarTwo->getKey()]);

        $response = $this->actingAs($userOne)->put('/calendars/' . $calendarOne->getKey(). '/slots/' . $slotTwo->getKey(), $this->formData);
        $response->assertStatus(404);
        $this->assertDatabaseMissing('slots', array_merge($this->formData, ['id' => $slotTwo->getKey()]));
    }
}
