<?php

namespace Tests\Feature\Properties;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreatePropertiesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an unauthorised user cannot create properties
     */
    public function testUnauthorizedAccess()
    {
        $this->post('/properties', []);

        $this->assertGuest();
    }

    /**
     * Test that a user cannot create properties
     */
    public function testUserCannotCreateProperties()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $user->assignRole(Role::findByName('user'));

        $response = $this->actingAs($user)->post('/properties',
            [
                'name' => 'Wynberg Squash Courts',
                'address' => 'Ave De Mist, Rondebosch, Cape Town, 7700',
                'contact_number' => '0214455678',
                'description' => 'Lorem Ipsum Dolor Set Amit',
                'is_private' => false
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('properties',
        [
            'name' => 'Wynberg Squash Courts',
            'address' => 'Ave De Mist, Rondebosch, Cape Town, 7700',
            'contact_number' => '0214455678',
            'description' => 'Lorem Ipsum Dolor Set Amit',
            'is_private' => false
        ]);
    }

    /**
     * Test that a team admin can create properties
     */
    public function testTeamAdminCanCreateProperties()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $user->assignRole(Role::findByName('user'));
        $user->assignRole(Role::findByName('team-admin'));

        $response = $this->actingAs($user)->post('/properties',
            [
                'name' => 'Wynberg Squash Courts',
                'address' => 'Ave De Mist, Rondebosch, Cape Town, 7700',
                'contact_number' => '0214455678',
                'description' => 'Lorem Ipsum Dolor Set Amit',
                'is_private' => false
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('properties',
            [
                'name' => 'Wynberg Squash Courts',
                'address' => 'Ave De Mist, Rondebosch, Cape Town, 7700',
                'contact_number' => '0214455678',
                'description' => 'Lorem Ipsum Dolor Set Amit',
                'is_private' => false
            ]);
    }
}
