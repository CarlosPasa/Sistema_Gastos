<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $expenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->from, function ($query) use ($request) {
                $query->whereDate('expense_date', '>=', $request->from);
            })
            ->when($request->to, function ($query) use ($request) {
                $query->whereDate('expense_date', '<=', $request->to);
            })
            ->latest()
            ->paginate(10);

        return response()->json($expenses);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
        ]);

        $this->validateCategoryOwnership($data['category_id']);

        $expense = Expense::create([
            'user_id' => auth()->id(),
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'expense_date' => $data['expense_date'],
        ]);

        return response()->json([
            'message' => 'Gasto creado correctamente.',
            'data' => $expense->load('category'),
        ], 201);
    }

    public function show(Expense $expense): JsonResponse
    {
        $this->authorizeExpense($expense);

        return response()->json([
            'data' => $expense->load('category'),
        ]);
    }

    public function update(Request $request, Expense $expense): JsonResponse
    {
        $this->authorizeExpense($expense);

        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
        ]);

        $this->validateCategoryOwnership($data['category_id']);

        $expense->update($data);

        return response()->json([
            'message' => 'Gasto actualizado correctamente.',
            'data' => $expense->load('category'),
        ]);
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $this->authorizeExpense($expense);

        $expense->delete();

        return response()->json([
            'message' => 'Gasto eliminado correctamente.',
        ]);
    }

    private function authorizeExpense(Expense $expense): void
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para acceder a este gasto.');
        }
    }

    private function validateCategoryOwnership(int $categoryId): void
    {
        $exists = Category::where('id', $categoryId)
            ->where('user_id', auth()->id())
            ->exists();

        if (! $exists) {
            abort(403, 'La categoría no pertenece al usuario autenticado.');
        }
    }
}
