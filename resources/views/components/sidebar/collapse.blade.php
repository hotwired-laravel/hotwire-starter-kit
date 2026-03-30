@props(['for'])

<label {{ $attributes->merge(['class' => 'hidden group-data-[collapsible]/sidebar:lg:inline-flex btn btn-sm btn-ghost btn-square cursor-pointer text-base-content/50']) }} for="{{ $for }}-collapse">
    <x-heroicon-o-chevron-double-left class="size-4 group-has-[:checked]/sidebar:lg:hidden" />
    <x-heroicon-o-chevron-double-right class="size-4 hidden group-has-[:checked]/sidebar:lg:block" />
    <span class="sr-only">{{ __('Toggle sidebar') }}</span>
</label>
