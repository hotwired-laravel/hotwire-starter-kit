@props(['on'])

<div x-data="{ shown: false, timeout: null }" x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms style="display: none" {{ $attributes->merge(['class' => 'text-sm']) }}>
    {{ $slot->isEmpty() ? __('Saved.') : $slot }}
</div>
