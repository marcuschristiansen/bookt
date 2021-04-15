<?php

namespace Tests\Feature\PropertyMemberships;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class GetAllPropertyMembershipsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpTestData();
    }

    /**
     * Test that an unauthorised user cannot create properties
     */
    public function testUnauthorizedAccess()
    {
        $this->get('/property-memberships');

        $this->assertGuest();
    }

    /**
     * Test fetch all the properties that the user is a member of
     */
    public function testFetchAllMembershipProperties()
    {
        $response = $this->actingAs($this->userOne)->get('/property-memberships');

        $response->assertInertia(fn (Assert $page) => $page
                ->component('Properties/Index')
                ->has('properties.data', null, fn (Assert $page) => $page
                    ->has('type')
                    ->has('id')
                    ->has('links')
                    ->where('attributes.team_id', $this->teamAdminOne->currentTeam->getKey())
                )
            );
    }
}
