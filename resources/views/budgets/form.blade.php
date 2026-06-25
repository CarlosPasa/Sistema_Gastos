<div class="mb-4">
    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Categoría</label>
    <select name="category_id" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
        <option value="">Selecciona una categoría</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                @selected(old('category_id', $budget->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Monto</label>
    <input type="number" step="0.01" name="amount"
           value="{{ old('amount', $budget->amount ?? '') }}"
           class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
    @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Mes</label>
        <input type="number" name="month" min="1" max="12"
               value="{{ old('month', $budget->month ?? now()->month) }}"
               class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Año</label>
        <input type="number" name="year"
               value="{{ old('year', $budget->year ?? now()->year) }}"
               class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
    </div>
</div>
