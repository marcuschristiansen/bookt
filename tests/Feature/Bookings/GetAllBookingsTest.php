<?php

namespace Tests\Feature\Bookings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAllBookingsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpTestData();
    }

    /**
     * Get all the bookings for a team admin that belong to the current team.
     *
     * @return void
     */
    public function test_get_all_bookings_that_belong_to_team()
    {
        $response = $this->actingAs($this->teamAdminOne)->get('/bookings');

        $response->assertStatus(200);
    }

    /**
     * Get all the bookings for a user
     *
     * @return void
     */
    public function test_get_all_bookings_that_belong_to_user()
    {
        $response = $this->actingAs($this->userOne)->get('/bookings');

        $response->assertStatus(200);
    }

    /**
     * Get all the bookings for a user
     *
     * @return void
     */
    public function test_get_all_bookings_that_belong_to_user_with_no_teams()
    {
        $response = $this->actingAs($this->userThree)->get('/bookings');

        $response->assertStatus(200);
    }
}
