<?php

namespace Tests\Feature\Calendars;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Calendar;
use App\Models\Property;
use App\Models\User;
use Inertia\Testing\Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetAllCalendarsTest extends TestCase
{
    /**
     * Test that an unauthorised user cannot create properties
     */
    public function testUnauthorizedAccess()
    {
        $this->get('/properties/1/calendars');

        $this->assertGuest();
    }

    /**
     * Test that a team admin can view all the calendars that belong to the property
     */
    public function testEndPointRedirects()
    {
        // Create a user with 2 teams and 3 properties for each team
        $user = User::factory()->withPersonalTeam()->create();
        $user->assignRole(Role::findByName('user'));
        $user->assignRole(Role::findByName('team-admin'));
        $teams = $user->ownedTeams;

        $properties = Property::factory(2)->create([
            'team_id' => $teams->first()->getKey(),
            'is_private' => true
        ])->each(fn (Property $property) =>
            Calendar::factory(3)->create(['property_id' => $property->getKey()]
        ));

        $response = $this->actingAs($user)->get('/properties/' . $properties->first()->getKey() . '/calendars');
        $response->assertStatus(302);
    }
}
