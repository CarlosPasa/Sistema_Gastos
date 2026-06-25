<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Nuevo gasto
        </h2>
    </x-slot>

    <div class="p-6">
        <x-form-card
            title="Registrar gasto"
            subtitle="Agrega un nuevo gasto y asígnalo a una categoría">

            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf

                @include('expenses.form')

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('expenses.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">
                        Guardar
                    </button>
                </div>
            </form>

        </x-form-card>
    </div>
</x-app-layout>
