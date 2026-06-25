<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Models\Budget;
use App\Models\Category;
use App\Services\BudgetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function __construct(
        private BudgetService $budgetService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $budgets = $this->budgetService->getAll();

        return view('budgets.index', compact('budgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('budgets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBudgetRequest $request): RedirectResponse
    {
        $this->budgetService->create($request->validated());

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Presupuesto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget): View
    {
        $categories = Category::orderBy('name')->get();

        return view('budgets.edit', compact('budget', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBudgetRequest $request, Budget $budget): RedirectResponse
    {
        $this->budgetService->update($budget, $request->validated());

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Presupuesto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget): RedirectResponse
    {
        $this->budgetService->delete($budget);

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Presupuesto eliminado correctamente.');
    }
}
