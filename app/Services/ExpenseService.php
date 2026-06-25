<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function getAll(array $filters = [])
    {
        return $this->buildQuery($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function create(array $data): Expense
    {
        $data['user_id'] = Auth::id();

        return Expense::create($data);
    }

    public function findById(int $id): Expense
    {
        return Expense::where('user_id', Auth::id())
            ->findOrFail($id);
    }

    public function update(int $id, array $data): bool
    {
        return $this->findById($id)->update($data);
    }

    public function delete(int $id): bool
    {
        $expense = $this->findById($id);

        return $this->findById($id)->delete();
    }

    public function getDashboardTotals(int $month, int $year): array
    {
        return [
            'total_expenses' => Expense::where('user_id', auth()->id())->sum('amount'),

            'monthly_expenses' => Expense::where('user_id', auth()->id())
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year)
                ->sum('amount'),

            'today_expenses' => Expense::where('user_id', auth()->id())
                ->whereDate('expense_date', today())
                ->sum('amount'),

            'expenses_by_category' => Expense::selectRaw('categories.name, SUM(expenses.amount) as total')
                ->join('categories', 'categories.id', '=', 'expenses.category_id')
                ->where('expenses.user_id', auth()->id())
                ->whereMonth('expenses.expense_date', $month)
                ->whereYear('expenses.expense_date', $year)
                ->groupBy('categories.name')
                ->orderByDesc('total')
                ->get(),
        ];
    }

    public function getForExport(array $filters = [])
    {
        return $this->buildQuery($filters)
            ->latest()
            ->get();
    }

    private function buildQuery(array $filters = [])
    {
        return Expense::with('category')
            ->where('user_id', auth()->id())
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%");
            })
            ->when($filters['category_id'] ?? null, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($filters['date_from'] ?? null, function ($query, $dateFrom) {
                $query->whereDate('expense_date', '>=', $dateFrom);
            })
            ->when($filters['date_to'] ?? null, function ($query, $dateTo) {
                $query->whereDate('expense_date', '<=', $dateTo);
            });
    }

    public function getBudgetVsSpent(int $month, int $year)
    {
        return Budget::with('category')
            ->where('user_id', auth()->id())
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->map(function ($budget) use ($month, $year) {

                $spent = Expense::where('user_id', auth()->id())
                    ->where('category_id', $budget->category_id)
                    ->whereMonth('expense_date', $month)
                    ->whereYear('expense_date', $year)
                    ->sum('amount');

                return [
                    'category' => $budget->category->name,
                    'budget' => (float) $budget->amount,
                    'spent' => (float) $spent,
                ];
            });
    }

    public function getBudgetAlerts(int $month, int $year)
    {
        return $this->getBudgetVsSpent($month, $year)
            ->map(function ($item) {
                $percentage = $item['budget'] > 0
                    ? round(($item['spent'] / $item['budget']) * 100, 2)
                    : 0;

                return [
                    'category' => $item['category'],
                    'budget' => $item['budget'],
                    'spent' => $item['spent'],
                    'percentage' => $percentage,
                ];
            })
            ->filter(fn ($item) => $item['percentage'] >= 80)
            ->values();
    }

    public function getBudgetSummary(int $month, int $year): array
    {
        $budgetVsSpent = $this->getBudgetVsSpent($month, $year);

        $totalBudget = $budgetVsSpent->sum('budget');
        $totalSpent = $budgetVsSpent->sum('spent');

        return [
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'available_balance' => $totalBudget - $totalSpent,
        ];
    }

    public function getLatestExpenses(int $month, int $year, int $limit = 5)
    {
        return Expense::with('category')
            ->where('user_id', auth()->id())
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
