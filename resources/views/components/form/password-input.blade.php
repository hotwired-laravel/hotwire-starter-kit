@props(['class' => ''])

<label class="input w-full join-item has-[[data-error]]:input-error {{ $class }}" data-controller="password-reveal">
    <x-heroicon-o-key aria-hidden="true" class="opacity-50 size-[1em]"/>

    <input {{ $attributes->merge(['type' => 'password', 'data-password-reveal-target' => 'input']) }} />

    <button class="relative btn btn-ghost btn-xs -mr-1.5 tooltip" aria-hidden="true" data-tip="{{ __('Reveal') }}" type="button" data-action="password-reveal#toggle turbo:before-cache@document->password-reveal#reset">
        <span class="grid grid-cols-1">
            <x-heroicon-o-eye aria-hidden="true" class="[:where([data-password-reveal-revealed-value=true]_&)]:hidden opacity-50 size-[1em] col-start-1 row-start-1"/>
            <x-heroicon-o-eye-slash aria-hidden="true" class="hidden [:where([data-password-reveal-revealed-value=true]_&)]:block! opacity-50 size-[1em] col-start-1 row-start-1"/>
        </span>

        <span class="sr-only">{{ __('Reveal') }}</span>
    </button>
</label>
