@props([
    'headers' => [],
])

<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <div class="relative overflow-x-auto">
        <table class="
            w-full
            text-sm
            text-left
            text-gray-500
            dark:text-gray-300
        ">
            <thead class="
                text-xs
                text-gray-700
                dark:text-gray-200
                uppercase
                bg-gray-100
                dark:bg-gray-700
            ">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="px-6 py-4 {{ $header['class'] ?? '' }}">
                            {{ $header['label'] }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
