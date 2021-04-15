<?php

namespace Tests\Feature\Properties;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetAllPropertiesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an unauthorised user cannot create properties
     */
    public function testUnauthorizedAccess()
    {
        $this->get('/properties');

        $this->assertGuest();
    }

    /**
     * Test fetch all the properties
     */
    public function testFetchAllOwnedProperties()
    {
        // Create a user with 2 teams and 3 properties for each team
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->teams()->create(['name' => 'Random Test Team', 'personal_team' => false, 'user_id' => $userOne->getKey()]);
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));
        $teams = $userOne->ownedTeams;

        Property::factory(3)->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ]);
        // Only fetch properties for the current team
        Property::factory(3)->create([
            'team_id' => $teams->last()->getKey(),
            'is_private' => false
        ]);

        $response = $this->actingAs($userOne)->get('/properties');
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
            $page
                ->component('Properties/Index')
                ->has('properties.data', 3)
                ->has('properties.data.0', fn (Assert $page) =>
                    $page
                        ->where('type', 'properties')
                        ->has('id')
                        ->has('links')
                        ->has('attributes', fn (Assert $page) =>
                            $page
                                ->where('team_id', (string)$teams->first()->getKey())
                                ->hasAll(['name', 'is_private', 'calendars', 'description', 'address', 'contact_number'])
                        )

                )
        );
    }

    /**
     * Test that user can fetch properties whose team they are part of
     */
    public function testFetchPropertiesForTeamMembers()
    {
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));

        $properties = Property::factory(2)->create([
            'team_id' => $userOne->personalTeam()->getKey(),
            'is_private' => true
        ]);

        // Create another user, switch to their team and fetch their properties
        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));
        $userTwo->assignRole(Role::findByName('team-admin'));
        $teamMember = new AddTeamMember();
        $teamMember->add($userOne, $userOne->personalTeam(), $userTwo->email, 'user');

        Property::factory(1)->create([
            'team_id' => $userTwo->personalTeam()->getKey(),
            'is_private' => true
        ]);

        $userTwo->switchTeam($userOne->personalTeam());

        $response = $this->actingAs($userTwo)->get('/properties');
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
            $page
                ->component('Properties/Index')
                ->has('properties.data', 2)
                ->has('properties.data.0', fn (Assert $page) =>
                    $page
                        ->where('type', 'properties')
                        ->where('id', $properties->first()->getKey())
                        ->has('links')
                        ->has('attributes', fn (Assert $page) =>
                            $page
                                ->where('team_id', (string)$userOne->personalTeam()->getKey())
                                ->hasAll(['name', 'is_private', 'calendars', 'description', 'address', 'contact_number'])
                        )

                    )
            );
    }

    /**
     * Test that any user can fetch a public property
     */
    public function testUserCannotFetchProperties()
    {
        $userOne = User::factory()->withPersonalTeam()->create();
        $userOne->assignRole(Role::findByName('user'));
        $userOne->assignRole(Role::findByName('team-admin'));

        $properties = Property::factory(2)->create([
            'team_id' => $userOne->personalTeam()->getKey(),
            'is_private' => false
        ]);

        $userTwo = User::factory()->withPersonalTeam()->create();
        $userTwo->assignRole(Role::findByName('user'));

        $response = $this->actingAs($userTwo)->get('/properties');
        $response->assertStatus(403);
    }
}
