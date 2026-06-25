<?php

test('input-label component includes dark mode label styles', function () {
    $output = view('components.input-label', ['value' => 'Nombre'])->render();

    expect($output)->toContain('dark:text-gray-300');
});

test('text-input component includes dark mode input styles', function () {
    $output = view('components.text-input', ['disabled' => false])->render();

    expect($output)->toContain('dark:bg-gray-700');
    expect($output)->toContain('dark:border-gray-600');
    expect($output)->toContain('dark:text-white');
});

test('secondary-button component includes dark mode button styles', function () {
    $output = view('components.secondary-button')->render();

    expect($output)->toContain('dark:bg-gray-800');
    expect($output)->toContain('dark:border-gray-600');
    expect($output)->toContain('dark:text-gray-200');
});
