<div class="mb-4">
    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Categoría</label>
    <select name="category_id" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
        <option value="">Selecciona una categoría</option>

        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                @selected(old('category_id', $expense->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    @error('category_id')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Fecha</label>
    <input type="date"
           name="expense_date"
           value="{{ old('expense_date', $expense->expense_date ?? now()->toDateString()) }}"
           class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">

    @error('expense_date')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Monto</label>
    <input type="number"
           step="0.01"
           name="amount"
           value="{{ old('amount', $expense->amount ?? '') }}"
           class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">

    @error('amount')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Descripción</label>
    <input type="text"
           name="description"
           value="{{ old('description', $expense->description ?? '') }}"
           class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">

    @error('description')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
