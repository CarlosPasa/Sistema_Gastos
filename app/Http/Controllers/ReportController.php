<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\Category;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only([
            'category_id',
            'date_from',
            'date_to',
            'search',
        ]);

        $query = Expense::with('category')
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

        $categories = Category::orderBy('name')->get();

        $total = (clone $query)->sum('amount');
        $count = (clone $query)->count();

        $expenses = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', compact(
            'expenses',
            'categories',
            'total',
            'count'
        ));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new ReportsExport($request->only([
                'search',
                'category_id',
                'date_from',
                'date_to',
            ])),
            'reporte-gastos.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only([
            'search',
            'category_id',
            'date_from',
            'date_to',
        ]);

        $expenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->when($filters['search'] ?? null, fn ($query, $search) =>
                $query->where('description', 'like', "%{$search}%")
            )
            ->when($filters['category_id'] ?? null, fn ($query, $categoryId) =>
                $query->where('category_id', $categoryId)
            )
            ->when($filters['date_from'] ?? null, fn ($query, $dateFrom) =>
                $query->whereDate('expense_date', '>=', $dateFrom)
            )
            ->when($filters['date_to'] ?? null, fn ($query, $dateTo) =>
                $query->whereDate('expense_date', '<=', $dateTo)
            )
            ->latest()
            ->get();

        $pdf = Pdf::loadView('reports.expenses-pdf', compact('expenses'));

        return $pdf->download('reporte-gastos.pdf');
    }
}
