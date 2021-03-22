<?php


namespace Tests\Feature\Slots;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateSlotTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpTestData();
    }

    /**
     * Test correct slot creation
     */
    public function testCreateSlot()
    {
        $response = $this->actingAs($this->teamAdminOne)->post('/slots', [
            'start_time' => '09:00',
            'end_time' => '10:00',
            'max_bookings' => 3
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('slots', [
            'team_id' => $this->teamAdminOne->currentTeam(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'max_bookings' => 3
        ]);

        $this->assertDatabaseHas('passes', [

        ]);
    }
}
