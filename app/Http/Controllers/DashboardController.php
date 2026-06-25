<?php

namespace App\Http\Controllers;

use App\Services\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected ExpenseService $expenseService
    ) {}

    public function index(Request $request): View
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $dashboard = $this->expenseService->getDashboardTotals($month, $year);
        $budgetVsSpent = $this->expenseService->getBudgetVsSpent($month, $year);
        $budgetAlerts = $this->expenseService->getBudgetAlerts($month, $year);
        $budgetSummary = $this->expenseService->getBudgetSummary($month, $year);
        $latestExpenses = $this->expenseService->getLatestExpenses($month, $year);

        $categoryLabels = $budgetVsSpent->pluck('category');
        $categoryTotals = $budgetVsSpent->pluck('spent');

        return view('dashboard', compact(
            'dashboard',
            'budgetVsSpent',
            'budgetAlerts',
            'budgetSummary',
            'latestExpenses',
            'categoryLabels',
            'categoryTotals',
            'month',
            'year'
        ));
    }
}
