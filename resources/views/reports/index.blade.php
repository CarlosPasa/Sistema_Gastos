<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Reportes
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Consulta y analiza tus gastos por rango de fechas.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('reports.export.excel', request()->query()) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 me-2"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5L12 15m0 0l4.5-4.5M12 15V3" />
                    </svg>

                    Excel
                </a>

                <a href="{{ route('reports.export.pdf', request()->query()) }}"
                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 me-2"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H6.75A2.25 2.25 0 004.5 4.5v15A2.25 2.25 0 006.75 21.75h10.5A2.25 2.25 0 0019.5 19.5v-5.25z" />
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13.5 2.25V6a2.25 2.25 0 002.25 2.25H19.5" />
                    </svg>

                    PDF
                </a>
            </div>
        </div>

        <form method="GET"
              action="{{ route('reports.index') }}"
              class="bg-white p-4 rounded-lg shadow-sm mb-6 dark:bg-gray-800 dark:border dark:border-gray-700">

            <div class="flex flex-wrap items-end gap-3">
                <div class="w-64">
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Buscar
                    </label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Descripción..."
                           class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                </div>

                <div class="w-56">
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Categoría
                    </label>
                    <select name="category_id" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                        <option value="">Todas</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Desde
                    </label>
                    <input type="date"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Hasta
                    </label>
                    <input type="date"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                </div>

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Filtrar
                </button>

                <a href="{{ route('reports.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    Limpiar
                </a>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total filtrado</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                    S/ {{ number_format($total, 2) }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Registros encontrados</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                    {{ $count }}
                </h2>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Promedio</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                </h2>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                Historial de gastos
            </h2>

            <x-table :headers="[
                ['label' => 'Fecha'],
                ['label' => 'Categoría'],
                ['label' => 'Descripción'],
                ['label' => 'Monto', 'class' => 'text-right'],
            ]">
                @forelse ($expenses as $expense)
                    <tr class="bg-white border-b hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-700 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ $expense->category->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ $expense->description }}
                        </td>

                        <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                            S/ {{ number_format($expense->amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron gastos.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>

        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
    </div>
</x-app-layout>
