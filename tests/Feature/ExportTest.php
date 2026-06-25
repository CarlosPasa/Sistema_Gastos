<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_export_expenses_excel(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('expenses.export'));

        $response->assertOk();

        $response->assertHeader(
            'content-type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
    }

    public function test_user_can_export_reports_pdf(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('reports.export.pdf'));

        $response->assertOk();

        $this->assertStringContainsString(
            'application/pdf',
            $response->headers->get('content-type')
        );
    }
}
