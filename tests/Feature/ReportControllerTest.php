<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    public function test_report_endpoint_successfully_returns_data()
    {
        Http::fake([
            '*' => Http::response([
                [
                    'plate' => 'ABC1234',
                    'make' => 'Fiat',
                    'model' => 'Uno',
                    'total_rentals' => 5,
                    'total_revenue' => 1500.00
                ]
            ], 200)
        ]);

        $this->actingAs(User::factory()->create(), 'api')
            ->getJson('/api/reports/revenue?start=2025-01-01&end=2025-12-31')
            ->assertStatus(200)
            ->assertJsonFragment([
                'make' => 'Fiat',
                'total_rentals' => 5
            ]);
    }

    public function test_report_requires_start_and_end_dates()
    {
        $this->actingAs(User::factory()->create(), 'api')
             ->getJson('/api/reports/revenue')
             ->assertStatus(422)
             ->assertJsonStructure(['message', 'errors' => ['start', 'end']]);
    }

    public function test_report_handles_unavailable_service()
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $this->actingAs(User::factory()->create(), 'api')
             ->getJson('/api/reports/revenue?start=2024-01-01&end=2024-12-31')
             ->assertStatus(500)
             ->assertJsonStructure(['error']);
    }
}