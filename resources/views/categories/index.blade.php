<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white">
                Categorías
            </h2>

            <a href="{{ route('categories.create') }}"
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

                Nueva categoría
            </a>
        </div>
    </x-slot>

    <div class="p-6">

        @if (session('success'))
            <div class="mb-4 p-4 text-green-800 bg-green-100 rounded-lg dark:bg-emerald-900/20 dark:text-emerald-200 dark:border dark:border-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <x-table :headers="[
            ['label' => 'Nombre'],
            ['label' => 'Descripción'],
            ['label' => 'Acciones', 'class' => 'text-right'],
        ]">

            @forelse ($categories as $category)
                <tr class="bg-white border-b hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-700 dark:hover:bg-gray-800">

                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                        {{ $category->name }}
                    </td>

                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        {{ $category->description }}
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">

                            <a href="{{ route('categories.edit', $category) }}"
                               class="p-2 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-300"
                               title="Editar">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor"
                                     class="w-5 h-5">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M16.862 4.487a2.25 2.25 0 1 1 3.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487Z" />
                                </svg>

                            </a>

                            <form action="{{ route('categories.destroy', $category) }}"
                                  method="POST"
                                  class="inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        onclick="return confirm('¿Eliminar categoría?')"
                                        class="p-2 rounded-full hover:bg-red-100 dark:hover:bg-red-900 text-red-600 dark:text-red-300"
                                        title="Eliminar">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor"
                                         class="w-5 h-5">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M6 7.5h12m-1.5 0v10.125A2.625 2.625 0 0 1 13.875 20.25h-3.75A2.625 2.625 0 0 1 7.5 17.625V7.5m3-3h3a1.5 1.5 0 0 1 1.5 1.5V7.5h-6V6A1.5 1.5 0 0 1 10.5 4.5Z" />
                                    </svg>

                                </button>

                            </form>

                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="3"
                        class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        No hay categorías registradas.
                    </td>
                </tr>
            @endforelse

        </x-table>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>

    </div>
</x-app-layout>
