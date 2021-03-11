<?php

namespace Tests\Feature\Bookings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAllBookingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_bookings_that_belong_to_team()
    {
        $response = $this->get('/bookings');

        $response->assertStatus(200);
    }
}
