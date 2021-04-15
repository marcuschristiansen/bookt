<?php

namespace Tests\Feature\Slots;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Calendar;
use App\Models\Property;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateSlotsTest extends TestCase
{
    /**
     * @var array $formData
     */
    public array $formData = [];

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->formData = [
            'start_time' => '09:00',
            'end_time' => '10:00',
            'day_id' => 1,
            'cost' => '50.00'
        ];
    }

    /**
     * Test that an unauthorised user cannot create properties
     */
    public function testUnauthorizedAccess()
    {
        $this->post('/calendars/1/slots', []);

        $this->assertGuest();
    }

    /**
     * Test that a team admin can create slots for their own property
     */
    public function testTeamAdminCanCreateSlots()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $user->assignRole(Role::findByName('user'));
        $user->assignRole(Role::findByName('team-admin'));
        $teams = $user->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);

        $calendar = Calendar::factory()->create(['property_id' => $property->getKey()]);

        $response = $this->actingAs($user)->post('/calendars/' . $calendar->getKey() . '/slots', $this->formData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('slots', array_merge($this->formData, ['calendar_id' => $calendar->getKey()]));
    }

    /**
     * Test that a team member can create calendars for the properties they are part of
     */
    public function testTeamMemberCanCreateSlots()
    {
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

        $response = $this->actingAs($userTwo)->post('/calendars/' . $calendar->getKey() . '/slots', $this->formData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('slots', array_merge($this->formData, ['calendar_id' => $calendar->getKey()]));
    }

    /**
     * Test that a user cannot create slots
     */
    public function testUserCannotCreateSlots()
    {
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

        $response = $this->actingAs($userTwo)->post('/calendars/' . $calendar->getKey() . '/slots', $this->formData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('slots', array_merge($this->formData, ['calendar_id' => $calendar->getKey()]));
    }

    /**
     * Test that a team admin cannot create slots for properties they do not own
     */
    public function testTeamAdminCannotCreateSlotsForAPropertyTheyDoNotOwn()
    {
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

        $response = $this->actingAs($userTwo)->post('/calendars/' . $calendar->getKey() . '/slots', $this->formData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('slots', array_merge($this->formData, ['calendar_id' => $calendar->getKey()]));
    }
}
