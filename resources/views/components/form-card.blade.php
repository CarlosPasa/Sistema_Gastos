@props([
    'title',
    'subtitle' => null,
])

<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">

    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ $title }}
        </h3>

        @if($subtitle)
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    <div class="p-6">
        {{ $slot }}
    </div>

</div>
