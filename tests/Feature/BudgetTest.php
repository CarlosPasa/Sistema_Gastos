<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_budget(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Casa',
            'description' => 'Gastos del hogar',
        ]);

        $response = $this->actingAs($user)->post(route('budgets.store'), [
            'category_id' => $category->id,
            'amount' => 1500,
            'month' => 6,
            'year' => 2026,
        ]);

        $response->assertRedirect(route('budgets.index'));

        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 1500,
            'month' => 6,
            'year' => 2026,
        ]);
    }

    public function test_cannot_create_duplicate_budget(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Servicios',
            'description' => 'Servicios',
        ]);

        $this->actingAs($user)->post(route('budgets.store'), [
            'category_id' => $category->id,
            'amount' => 120,
            'month' => 6,
            'year' => 2026,
        ]);

        $response = $this->actingAs($user)->post(route('budgets.store'), [
            'category_id' => $category->id,
            'amount' => 200,
            'month' => 6,
            'year' => 2026,
        ]);

        $response->assertSessionHasErrors();
    }
}
