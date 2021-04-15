<?php

namespace Tests\Feature\Properties;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeletePropertiesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a team admin can delete their owned property
     */
    public function testTeamAdminCanDeleteProperty()
    {
        // Create a user with team and one private property
        $user = User::factory()->withPersonalTeam()->create();
        $user->assignRole(Role::findByName('user'));
        $user->assignRole(Role::findByName('team-admin'));
        $team = $user->personalTeam();
        $property = Property::factory()->create([
            'team_id' => $team->getKey(),
            'is_private' => true
        ]);

        $response = $this->actingAs($user)->delete('/properties/' . $property->getKey());
        $response->assertStatus(302);
        $this->assertSoftDeleted('properties', ['id' => $property->getKey()]);
    }

    /**
     * Test that a team admin cannot delete a property they do not own
     */
    public function testTeamAdminCannotDeleteAPropertyTheyDoNotOwn()
    {
        // Create a user with team and one private property
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $team = $userOne->personalTeam();
        $property = Property::factory()->create([
            'team_id' => $team->getKey(),
            'is_private' => true
        ]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));

        $response = $this->actingAs($userTwo)->delete('/properties/' . $property->getKey());
        $response->assertStatus(403);
        $this->assertDatabaseHas('properties', ['id' => $property->getKey(), 'deleted_at' => null]);
    }

    /**
     * Test that a team member cannot delete a property
     */
    public function testTeamMemberCannotDeleteProperty()
    {
        // Create a user with team and one private property
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $team = $userOne->personalTeam();
        $property = Property::factory()->create([
            'team_id' => $team->getKey(),
            'is_private' => true
        ]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $team, $userTwo->email, 'user');

        $response = $this->actingAs($userTwo)->delete('/properties/' . $property->getKey());
        $response->assertStatus(403);
        $this->assertDatabaseHas('properties', ['id' => $property->getKey(), 'deleted_at' => null]);
    }

    /**
     * Test that a user cannot delete any properties
     */
    public function testUserCannotDeleteAProperty()
    {
        // Create a user with team and one private property
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $team = $userOne->personalTeam();
        $property = Property::factory()->create([
            'team_id' => $team->getKey(),
            'is_private' => true
        ]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->delete('/properties/' . $property->getKey());
        $response->assertStatus(403);
        $this->assertDatabaseHas('properties', ['id' => $property->getKey(), 'deleted_at' => null]);
    }
}
