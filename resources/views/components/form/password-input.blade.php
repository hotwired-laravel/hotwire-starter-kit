@props(['class' => ''])

<label class="input w-full join-item has-[[data-error]]:input-error {{ $class }}" data-controller="password-reveal">
    <x-icons.key class="opacity-50" size="h-[1em]" aria-hidden="true" />

    <input {{ $attributes->merge(['type' => 'password', 'data-password-reveal-target' => 'input']) }} />

    <button class="relative btn btn-ghost btn-xs -mr-1.5 tooltip" aria-hidden="true" data-tip="{{ __('Reveal') }}" type="button" data-action="password-reveal#toggle turbo:before-cache@document->password-reveal#reset">
        <x-icons.eye size="h-[1em]" class="[:where([data-password-reveal-revealed-value=true]_&)]:hidden" />
        <x-icons.eye-slash size="h-[1em]" class="hidden [:where([data-password-reveal-revealed-value=true]_&)]:block!" />
        <span class="sr-only">{{ __('Reveal') }}</span>
    </button>
</label>
