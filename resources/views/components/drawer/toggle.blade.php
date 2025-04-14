@props(['icon'])
<label {{ $attributes->merge(['class' => 'btn btn-ghost drawer-button']) }}>
    <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />

    <span class="sr-only">{{ __('Toggle sidebar') }}</span>
</label>
