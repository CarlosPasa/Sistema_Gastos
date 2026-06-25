<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">

        <button
            data-drawer-target="sidebar"
            data-drawer-toggle="sidebar"
            aria-controls="sidebar"
            type="button"
            class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100"
        >
            <span class="sr-only">Abrir menú</span>
            ☰
        </button>

        <aside
            id="sidebar"
            class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
            aria-label="Sidebar"
        >
            <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="flex items-center ps-2.5 mb-5">
                    <span class="self-center text-xl font-semibold whitespace-nowrap">
                        Sistema Gastos
                    </span>
                </a>

                <ul class="space-y-2 font-medium">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center p-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="me-3">📊</span>
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('categories.index') }}"
                           class="flex items-center p-2 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="me-3">🏷️</span>
                            Categorías
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('expenses.index') }}"
                           class="flex items-center p-2 rounded-lg {{ request()->routeIs('expenses.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="me-3">💸</span>
                            Gastos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('budgets.index') }}"
                           class="flex items-center p-2 rounded-lg {{ request()->routeIs('budgets.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="me-3">🎯</span>
                            Presupuestos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}"
                        class="flex items-center p-2 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="me-3">📄</span>
                            Reportes
                        </a>
                    </li>
                    <li>
                        <button id="theme-toggle"
                                class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-blue-300">

                            <svg class="w-5 h-5 me-3"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21.752 15.002A9.718 9.718 0 0112 21a9 9 0 110-18 9.718 9.718 0 019.752 6.002A7.5 7.5 0 0012 18a7.5 7.5 0 009.752-2.998z" />
                            </svg>

                            <span>Modo oscuro</span>
                        </button>
                    </li>
                </ul>

                <div class="absolute bottom-4 left-3 right-3">
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-500 mb-2">
                            {{ Auth::user()->name }}
                        </p>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="w-full text-left p-2 text-red-600 rounded-lg hover:bg-red-50">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="p-4 sm:ml-64">
            @isset($header)
                <header class="bg-white shadow-sm rounded-lg mb-6 dark:bg-gray-900 dark:text-white">
                    <div class="px-6 py-4">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggleBtn = document.getElementById('theme-toggle');

        function applyTheme() {
            const theme = localStorage.getItem('color-theme');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        applyTheme();

        themeToggleBtn?.addEventListener('click', function () {
            const isDark = document.documentElement.classList.contains('dark');

            if (isDark) {
                localStorage.setItem('color-theme', 'light');
            } else {
                localStorage.setItem('color-theme', 'dark');
            }

            applyTheme();
            });
        });
    </script>
</body>
</html>
