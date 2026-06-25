<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class BudgetService
{
    public function getAll()
    {
        return Budget::with('category')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10)
            ->through(function ($budget) {
                $spent = Expense::where('user_id', auth()->id())
                    ->where('category_id', $budget->category_id)
                    ->whereMonth('expense_date', $budget->month)
                    ->whereYear('expense_date', $budget->year)
                    ->sum('amount');

                $budget->spent = $spent;
                $budget->difference = $budget->amount - $spent;
                $budget->percentage = $budget->amount > 0
                    ? round(($spent / $budget->amount) * 100, 2)
                    : 0;

                return $budget;
            });
    }

    public function create(array $data): Budget
    {
        $data['user_id'] = Auth::id();

        return Budget::create($data);
    }

    public function update(Budget $budget, array $data): bool
    {
        return $budget->update($data);
    }

    public function delete(Budget $budget): bool
    {
        return $budget->delete();
    }
}
