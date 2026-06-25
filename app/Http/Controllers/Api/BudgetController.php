<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $budgets = Budget::with('category')
            ->where('user_id', auth()->id())
            ->when($request->month, function ($query) use ($request) {
                $query->where('month', $request->month);
            })
            ->when($request->year, function ($query) use ($request) {
                $query->where('year', $request->year);
            })
            ->latest()
            ->get();

        return response()->json([
            'data' => $budgets,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020'],
        ]);

        $this->validateCategoryOwnership($data['category_id']);

        $budget = Budget::create([
            'user_id' => auth()->id(),
            'category_id' => $data['category_id'],
            'amount' => $data['amount'],
            'month' => $data['month'],
            'year' => $data['year'],
        ]);

        return response()->json([
            'message' => 'Presupuesto creado correctamente.',
            'data' => $budget->load('category'),
        ], 201);
    }

    public function show(Budget $budget): JsonResponse
    {
        $this->authorizeBudget($budget);

        return response()->json([
            'data' => $budget->load('category'),
        ]);
    }

    public function update(Request $request, Budget $budget): JsonResponse
    {
        $this->authorizeBudget($budget);

        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020'],
        ]);

        $this->validateCategoryOwnership($data['category_id']);

        $budget->update($data);

        return response()->json([
            'message' => 'Presupuesto actualizado correctamente.',
            'data' => $budget->load('category'),
        ]);
    }

    public function destroy(Budget $budget): JsonResponse
    {
        $this->authorizeBudget($budget);

        $budget->delete();

        return response()->json([
            'message' => 'Presupuesto eliminado correctamente.',
        ]);
    }

    private function authorizeBudget(Budget $budget): void
    {
        if ($budget->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para acceder a este presupuesto.');
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
