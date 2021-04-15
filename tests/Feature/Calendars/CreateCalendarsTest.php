<?php

namespace Tests\Feature\Calendars;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Property;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateCalendarsTest extends TestCase
{
    /**
     * Test that an unauthorised user cannot create properties
     */
    public function testUnauthorizedAccess()
    {
        $this->post('/properties/1/calendars', []);

        $this->assertGuest();
    }

    /**
     * Test that a team admin can create calendars for their own property
     */
    public function testTeamAdminCanCreateCalendars()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $user->assignRole(Role::findByName('user'));
        $user->assignRole(Role::findByName('team-admin'));
        $teams = $user->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);

        $response = $this->actingAs($user)->post('/properties/' . $property->getKey() . '/calendars',
            [
                'name' => 'Squash Court One',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('calendars',
            [
                'name' => 'Squash Court One',
                'property_id' => $property->getKey(),
            ]);
    }

    /**
     * Test that a team member can create calendars for the properties they are part of
     */
    public function testTeamMemberCanCreateCalendars()
    {
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $teams->first(), $userTwo->email, 'user');

        $response = $this->actingAs($userTwo)->post('/properties/' . $property->getKey() . '/calendars',
            [
                'name' => 'Squash Court Two',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('calendars',
            [
                'name' => 'Squash Court Two',
                'property_id' => $property->getKey(),
            ]);
    }

    /**
     * Test that a user cannot create calendars
     */
    public function testUserCannotCreateCalendars()
    {
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->post('/properties/' . $property->getKey() . '/calendars',
            [
                'name' => 'Squash Court Three',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('calendars',
            [
                'name' => 'Squash Court Three',
                'property_id' => $property->getKey(),
            ]);
    }

    /**
     * Test that a team admin cannot create calendars for properties they do not own
     */
    public function testTeamAdminCannotCreateCalendarsForAPropertyTheyDoNotOwn()
    {
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        $property = Property::factory()->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));

        $response = $this->actingAs($userTwo)->post('/properties/' . $property->getKey() . '/calendars',
            [
                'name' => 'Squash Court Four',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('calendars',
            [
                'name' => 'Squash Court Four',
                'property_id' => $property->getKey(),
            ]);
    }
}
