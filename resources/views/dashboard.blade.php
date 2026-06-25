<x-app-layout>
    <div class="p-6 sm:p-8 bg-gray-50 min-h-screen dark:bg-gray-900 dark:text-white">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Dashboard
                </h1>
                <p class="text-gray-500 mt-1 dark:text-gray-400">
                    Resumen general de tus gastos
                </p>
            </div>

            <form method="GET" action="{{ route('dashboard') }}" class="bg-white p-4 rounded-lg shadow-sm mb-6 dark:bg-gray-800 dark:border dark:border-gray-700">
                <div class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Mes</label>
                        <select name="month" class="rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" @selected($month == $m)>
                                    {{ ucfirst(\Carbon\Carbon::create()->month($m)->locale('es')->translatedFormat('F')) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Año</label>
                        <input type="number"
                            name="year"
                            value="{{ $year }}"
                            class="rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Filtrar
                    </button>

                    <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        Actual
                    </a>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Total gastado
                            </p>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                S/ {{ number_format($dashboard['total_expenses'], 2) }}
                            </h2>
                        </div>

                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 text-2xl">💰</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Gastos del mes
                            </p>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                S/ {{ number_format($dashboard['monthly_expenses'], 2) }}
                            </h2>
                        </div>

                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-green-600 text-2xl">📅</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Gastos de hoy
                            </p>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                S/ {{ number_format($dashboard['today_expenses'], 2) }}
                            </h2>
                        </div>

                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <span class="text-red-600 text-2xl">🔥</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Saldo disponible
                            </p>
                            <h2 class="text-3xl font-bold mt-2 {{ $budgetSummary['available_balance'] < 0 ? 'text-red-600 dark:text-red-300' : 'text-gray-900 dark:text-gray-100' }}">
                                S/ {{ number_format($budgetSummary['available_balance'], 2) }}
                            </h2>
                        </div>

                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <span class="text-purple-600 text-2xl">💼</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($budgetAlerts->isNotEmpty())
                <div class="mb-8 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4 dark:bg-yellow-900/20 dark:border-yellow-700 dark:text-yellow-200">
                    <h3 class="font-semibold mb-2">Alertas de presupuesto</h3>

                    <ul class="space-y-1">
                        @foreach ($budgetAlerts as $alert)
                            <li>
                                @if ($alert['percentage'] >= 100)
                                    🚨 {{ $alert['category'] }} superó el presupuesto:
                                @else
                                    ⚠️ {{ $alert['category'] }} está cerca del límite:
                                @endif

                                {{ $alert['percentage'] }}%
                                — S/ {{ number_format($alert['spent'], 2) }}
                                de S/ {{ number_format($alert['budget'], 2) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Presupuesto vs Gastado
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">
                        Comparación del mes actual por categoría
                    </p>
                </div>

                <div class="p-6">
                    <div id="budgetVsSpentChart"></div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Gastos por Categoría
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">
                        Comparación de gastos acumulados por categoría
                    </p>
                </div>
                 <div class="p-6 space-y-6 w-80 h-80 mx-auto">
                    <canvas id="expensesPieChart"></canvas>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Estado de presupuestos
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">
                        Avance del gasto por categoría
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    @forelse ($budgetVsSpent as $item)
                        @php
                            $percentage = $item['budget'] > 0
                                ? round(($item['spent'] / $item['budget']) * 100, 2)
                                : 0;

                            $barWidth = min($percentage, 100);

                            $barColor = $percentage >= 100
                                ? 'bg-red-600'
                                : ($percentage >= 80 ? 'bg-yellow-400' : 'bg-green-600');
                        @endphp

                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="font-medium text-gray-900">
                                    {{ $item['category'] }}
                                </span>

                                <span class="text-sm font-medium text-gray-700">
                                    {{ $percentage }}%
                                </span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                                <div class="{{ $barColor }} h-3 rounded-full"
                                    style="width: {{ $barWidth }}%">
                                </div>
                            </div>

                            <p class="text-sm text-gray-500 mt-2 dark:text-gray-400">
                                S/ {{ number_format($item['spent'], 2) }}
                                de
                                S/ {{ number_format($item['budget'], 2) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">
                            No hay presupuestos registrados para este mes.
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Resumen visual por categoría
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">
                        Comparación de gastos acumulados
                    </p>
                </div>

                <div class="p-6">
                    <div id="expensesByCategoryChart"></div>
                </div>
            </div>

            <!-- <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Gastos por categoría
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Distribución acumulada por categoría
                    </p>
                </div>

                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4">
                                    Categoría
                                </th>
                                <th scope="col" class="px-6 py-4 text-right">
                                    Total
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($dashboard['expenses_by_category'] as $category)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                        S/ {{ number_format($category->total, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-gray-500">
                                        No hay gastos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div> -->

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Últimos gastos
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">
                        Movimientos recientes registrados
                    </p>
                </div>

                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:text-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Categoría</th>
                                <th class="px-6 py-4">Descripción</th>
                                <th class="px-6 py-4 text-right">Monto</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($latestExpenses as $expense)
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
                                        No hay gastos recientes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        const categoryLabels = @json($dashboard['expenses_by_category']->pluck('name'));
        const categoryTotals = @json($dashboard['expenses_by_category']->pluck('total'));

        const options = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Total gastado',
                data: categoryTotals
            }],
            xaxis: {
                categories: categoryLabels
            },
            dataLabels: {
                enabled: false
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return 'S/ ' + value.toFixed(2);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return 'S/ ' + value.toFixed(2);
                    }
                }
            }
        };

        const chart = new ApexCharts(
            document.querySelector("#expensesByCategoryChart"),
            options
        );

        chart.render();
    </script>

    <script>
        const budgetCategories = @json($budgetVsSpent->pluck('category'));
        const budgetAmounts = @json($budgetVsSpent->pluck('budget'));
        const spentAmounts = @json($budgetVsSpent->pluck('spent'));

        const budgetVsSpentOptions = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            series: [
                {
                    name: 'Presupuesto',
                    data: budgetAmounts
                },
                {
                    name: 'Gastado',
                    data: spentAmounts
                }
            ],
            xaxis: {
                categories: budgetCategories
            },
            dataLabels: {
                enabled: false
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return 'S/ ' + value.toFixed(2);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return 'S/ ' + value.toFixed(2);
                    }
                }
            }
        };

        new ApexCharts(
            document.querySelector("#budgetVsSpentChart"),
            budgetVsSpentOptions
        ).render();
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const ctx = document.getElementById('expensesPieChart');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryTotals)
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

    });
    </script>
</x-app-layout>

