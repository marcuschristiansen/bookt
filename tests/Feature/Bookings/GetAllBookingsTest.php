<?php

namespace Tests\Feature\Bookings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAllBookingsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

//        $this->setUpTestData();
    }

    /**
     * Get all the bookings for a team admin that belong to the current team.
     *
     * @return void
     */
    public function testGetAllBookingsForProperty()
    {
        $this->markTestSkipped('Incomplete');

//        $properties = Property::where('team_id', $this->teamAdminOne->currentTeam->getKey())->get();
//        $properties->each(function(Property $property) {
//            $bookings = Booking::where('property_id', $property->getKey())->get();
//
//            $this->actingAs($this->teamAdminOne)->get('/bookings?property=' . $property->getKey())
//                ->assertInertia(fn (Assert $page) => $page
//                    ->component('Bookings/Index')
//                    ->has('bookings.data', null, fn (Assert $page) => $page
//                        ->has('type')
//                        ->has('id')
//                        ->has('links')
//                        ->where('attributes.property_id', $property->getKey())
//                    )
//                );
//        });
//        $response->assertStatus(200);
    }

    /**
     * Get all the bookings for a user
     *
     * @return void
     */
    public function testGetAllBookingsForUser()
    {
        $this->markTestSkipped('Incomplete');

//        $response = $this->actingAs($this->userOne)->get('/bookings');
//
//        $response->assertStatus(200);
    }
}
