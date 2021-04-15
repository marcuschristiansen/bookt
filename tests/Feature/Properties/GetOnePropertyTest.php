<?php

namespace Tests\Feature\Properties;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetOnePropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an unauthorised user cannot fetch any properties
     */
    public function testUnauthorizedAccess()
    {
        $this->get('/properties/1');

        $this->assertGuest();
    }

    /**
     * Test that a user cant view a property
     */
    public function testUserCantViewAProperty()
    {
        // Create a user with team and one public property
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $team = $userOne->personalTeam();
        $publicProperty = Property::factory()->create([
            'team_id' => $team->getKey(),
            'is_private' => false
        ]);

        // Create another user to access the public property
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->get('/properties/' . $publicProperty->getKey());
        $response->assertStatus(403);
    }

    /**
     * Test that a user can view a private property that they are a member of
     */
    public function testTeamMemberCanViewPrivatePropertyTheyAreAMemberOf()
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

        // Create another user and add to private property as property member
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $userOne->personalTeam(), $userTwo->email, 'user');

        $response = $this->actingAs($userTwo)->get('/properties/' . $privateProperty->getKey());
        $response->assertStatus(200);
        $response->assertInertia(function(Assert $page) use ($privateProperty, $team) {
            $page->component('Properties/Show')
                ->has('property.data', null, function(Assert $page) use ($privateProperty, $team) {
                    $page->has('type')
                        ->has('id')
                        ->has('links')
                        ->where('type', 'properties')
                        ->where('id', $privateProperty->getKey())
                        ->where('attributes.is_private', true)
                        ->where('attributes.team_id', $team->getKey());
                });
        });
    }

    /**
     * Test that a user cann view a private property they own
     */
    public function testUserCanViewPrivatePropertyTheyOwn()
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

        $response = $this->actingAs($userOne)->get('/properties/' . $privateProperty->getKey());
        $response->assertStatus(200);
        $response->assertInertia(function(Assert $page) use ($privateProperty, $team) {
            $page->component('Properties/Show')
                ->has('property.data', null, function(Assert $page) use ($privateProperty, $team) {
                    $page->has('type')
                        ->has('id')
                        ->has('links')
                        ->where('type', 'properties')
                        ->where('id', $privateProperty->getKey())
                        ->where('attributes.is_private', true)
                        ->where('attributes.team_id', $team->getKey());
                });
        });
    }
}
