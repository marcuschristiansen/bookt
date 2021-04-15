<?php

namespace Tests\Feature\Properties;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdatePropertiesTest extends TestCase
{
    use RefreshDatabase;

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
            'name' => 'New updated property name',
            'address' => 'Brand new street address',
            'contact_number' => '+270824567733',
            'description' => 'New and updated description',
            'is_private' => true
        ];
    }

    /**
     * Test that an unauthorised user cannot update properties
     */
    public function testUnauthorizedAccess()
    {
        $this->put('/properties', []);

        $this->assertGuest();
    }

    /**
     * Test that a property owner can update their own property
     */
    public function testPropertyOwnerCanUpdateProperty()
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

        $response = $this->actingAs($user)->put('/properties/' . $property->getKey(), $this->formUpdates);
        $response->assertStatus(302);
        $this->assertDatabaseHas('properties', $this->formUpdates);
    }

    /**
     * Test that a team member can update a property
     */
    public function testTeamMemberCanUpdateProperty()
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

        $response = $this->actingAs($userTwo)->put('/properties/' . $property->getKey(), $this->formUpdates);
        $response->assertStatus(302);
        $this->assertDatabaseHas('properties', $this->formUpdates);
    }

    /**
     * Test that a team admin cannot update a property they do not own
     */
    public function testTeamAdminRoleCannotUpdateAPropertyTheyDontOwn()
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

        $response = $this->actingAs($userTwo)->put('/properties/' . $property->getKey(), $this->formUpdates);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('properties', $this->formUpdates);
    }

    /**
     * Test that a user role cannot update any properties
     */
    public function testUserCannotUpdateAnyProperties()
    {
        // Create a user with team and one private property
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $team = $userOne->personalTeam();
        $privateProperty = Property::factory()->create([
            'team_id' => $team->getKey(),
            'is_private' => true
        ]);

        // Create a normal user to try update a property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->addToProperty($privateProperty);

        $response = $this->actingAs($userTwo)->put('/properties/' . $privateProperty->getKey(), $this->formUpdates);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('properties', $this->formUpdates);
    }
}
