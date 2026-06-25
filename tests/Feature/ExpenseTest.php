<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_expense(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Servicios',
            'description' => 'Pagos de servicios',
        ]);

        $response = $this->actingAs($user)->post(route('expenses.store'), [
            'category_id' => $category->id,
            'expense_date' => '2026-06-16',
            'amount' => 100,
            'description' => 'Pago de luz',
        ]);

        $response->assertRedirect(route('expenses.index'));

        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 100,
            'description' => 'Pago de luz',
        ]);
    }

    public function test_user_cannot_update_expense_from_another_user(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $category = Category::create([
            'name' => 'Servicios',
            'description' => 'Pagos de servicios',
        ]);

        $expense = Expense::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'expense_date' => '2026-06-16',
            'amount' => 100,
            'description' => 'Pago de luz',
        ]);

        $response = $this->actingAs($otherUser)
            ->put(route('expenses.update', $expense), [
                'category_id' => $category->id,
                'expense_date' => '2026-06-17',
                'amount' => 500,
                'description' => 'Intento de cambio',
            ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'user_id' => $owner->id,
            'amount' => 100,
            'description' => 'Pago de luz',
        ]);
    }

    public function test_user_cannot_delete_expense_from_another_user(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $category = Category::create([
            'name' => 'Servicios',
            'description' => 'Servicios',
        ]);

        $expense = Expense::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'expense_date' => '2026-06-16',
            'amount' => 100,
            'description' => 'Pago de luz',
        ]);

        $response = $this->actingAs($otherUser)
            ->delete(route('expenses.destroy', $expense));

        $response->assertNotFound();

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
        ]);
    }
}
