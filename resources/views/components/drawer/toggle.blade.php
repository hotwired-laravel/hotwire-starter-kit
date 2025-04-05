@props(['icon'])
<label {{ $attributes->merge(['class' => 'btn btn-ghost drawer-button']) }}>
    <x-dynamic-component :component="'icons.'.$icon" />

    <span class="sr-only">{{ __('Toggle sidebar') }}</span>
</label>
