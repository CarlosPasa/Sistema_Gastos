<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Presupuestos
            </h1>

            <a href="{{ route('budgets.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors dark:bg-blue-600 dark:hover:bg-blue-700">
                <svg class="w-5 h-5 me-2"
                     xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nuevo presupuesto
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 text-green-800 bg-green-100 rounded-lg dark:bg-emerald-900/20 dark:text-emerald-200 dark:border dark:border-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <x-table :headers="[
            ['label' => 'Categoría'],
            ['label' => 'Periodo'],
            ['label' => 'Presupuesto', 'class' => 'text-right'],
            ['label' => 'Gastado', 'class' => 'text-right'],
            ['label' => 'Diferencia', 'class' => 'text-right'],
            ['label' => 'Estado'],
            ['label' => 'Acciones', 'class' => 'text-right'],
        ]">
            @forelse ($budgets as $budget)
                <tr class="bg-white border-b hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-700 dark:hover:bg-gray-800">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                        {{ $budget->category->name }}
                    </td>

                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        {{ str_pad($budget->month, 2, '0', STR_PAD_LEFT) }}/{{ $budget->year }}
                    </td>

                    <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-gray-100">
                        S/ {{ number_format($budget->amount, 2) }}
                    </td>

                    <td class="px-6 py-4 text-right text-gray-700 dark:text-gray-300">
                        S/ {{ number_format($budget->spent, 2) }}
                    </td>

                    <td class="px-6 py-4 text-right {{ $budget->difference < 0 ? 'text-red-600 dark:text-red-300 font-semibold' : 'text-green-600 dark:text-green-300 font-semibold' }}">
                        S/ {{ number_format($budget->difference, 2) }}
                    </td>

                    <td class="px-6 py-4">
                        @if ($budget->percentage >= 100)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                                Excedido {{ $budget->percentage }}%
                            </span>
                        @elseif ($budget->percentage >= 80)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200">
                                Alerta {{ $budget->percentage }}%
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-emerald-900 dark:text-emerald-200">
                                OK {{ $budget->percentage }}%
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('budgets.edit', $budget) }}"
                               class="p-2 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-300"
                               title="Editar">
                                ✏️
                            </a>

                            <form action="{{ route('budgets.destroy', $budget) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        onclick="return confirm('¿Eliminar presupuesto?')"
                                        class="p-2 rounded-full hover:bg-red-100 dark:hover:bg-red-900 text-red-600 dark:text-red-300"
                                        title="Eliminar">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        No hay presupuestos registrados.
                    </td>
                </tr>
            @endforelse
        </x-table>

        <div class="mt-4">
            {{ $budgets->links() }}
        </div>
    </div>
</x-app-layout>
