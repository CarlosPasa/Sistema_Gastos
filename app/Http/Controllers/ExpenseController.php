<?php

namespace App\Http\Controllers;

use App\Exports\ExpensesExport;
use App\Http\Requests\StoreExpenseRequest;
use App\Models\Category;
use App\Models\Expense;
use App\Services\ExpenseService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{

    protected ExpenseService $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expenses = $this->expenseService->getAll($request->only([
            'search',
            'category_id',
            'date_from',
            'date_to',
        ]));

        $categories = Category::orderBy('name')->get();

        return view('expenses.index', compact('expenses', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $this->expenseService->create($request->validated());

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Gasto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $expense = $this->expenseService->findById($id);
        $categories = Category::orderBy('name')->paginate(10);

        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreExpenseRequest $request, int $id): RedirectResponse
    {
        $this->expenseService->update($id, $request->validated());

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Gasto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->expenseService->delete($id);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Gasto eliminado correctamente.');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new ExpensesExport($request->only([
                'search',
                'category_id',
                'date_from',
                'date_to',
            ])),
            'gastos.xlsx'
        );
    }

    public function pdf(Request $request)
    {
        $expenses = $this->expenseService->getForExport($request->only([
            'search',
            'category_id',
            'date_from',
            'date_to',
        ]));

        $pdf = Pdf::loadView('reports.expenses-pdf', compact('expenses'));

        return $pdf->download('gastos.pdf');
    }
}
