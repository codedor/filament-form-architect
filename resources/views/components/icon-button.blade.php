@props([
    'action',
    'statePath',
    'arguments' => [],
    'color' => 'gray',
])

@php
    $wireClickActionArguments = \Illuminate\Support\Js::from($arguments);
    $wireClickAction = "mountFormComponentAction('{$statePath}', '{$action->getName()}', {$wireClickActionArguments})"
@endphp

<x-filament::icon-button
    :color="$color"
    :wire:click="$wireClickAction"
    :icon="$action->getIcon()"
    class="border-2 bg-white"
    size="sm"
    icon-size="sm"
/>
