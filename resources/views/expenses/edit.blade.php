<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Editar gasto
        </h2>
    </x-slot>

    <div class="p-6">

        <x-form-card
            title="Editar gasto"
            subtitle="Actualiza la información del gasto seleccionado">

            <form action="{{ route('expenses.update', $expense) }}"
                  method="POST">

                @csrf
                @method('PUT')

                @include('expenses.form')

                <div class="mt-6 flex justify-end gap-3">

                    <a href="{{ route('expenses.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">

                        <svg class="w-4 h-4 me-2"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>

                        Actualizar
                    </button>

                </div>

            </form>

        </x-form-card>

    </div>
</x-app-layout>
