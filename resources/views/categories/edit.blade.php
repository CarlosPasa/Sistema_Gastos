<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Editar categoría
        </h2>
    </x-slot>

    <div class="p-6">

        <x-form-card
            title="Editar categoría"
            subtitle="Actualiza la información de la categoría seleccionada">

            <form method="POST"
                  action="{{ route('categories.update', $category) }}">

                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="name"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        placeholder="Ej: Servicios"
                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg
                               focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Descripción
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Describe esta categoría..."
                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg
                               focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">{{ old('description', $category->description) }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
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

                    <a href="{{ route('categories.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">
                        Cancelar
                    </a>
                </div>

            </form>

        </x-form-card>

    </div>
</x-app-layout>
