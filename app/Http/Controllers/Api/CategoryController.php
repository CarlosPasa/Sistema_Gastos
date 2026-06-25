<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category = Category::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
        ]);

        return response()->json([
            'message' => 'Categoría creada correctamente.',
            'data' => $category,
        ], 201);
    }

    public function show(Category $category): JsonResponse
    {
        $this->authorizeCategory($category);

        return response()->json([
            'data' => $category,
        ]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $this->authorizeCategory($category);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update($data);

        return response()->json([
            'message' => 'Categoría actualizada correctamente.',
            'data' => $category,
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->authorizeCategory($category);

        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente.',
        ]);
    }

    private function authorizeCategory(Category $category): void
    {
        if ($category->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para acceder a esta categoría.');
        }
    }
}
