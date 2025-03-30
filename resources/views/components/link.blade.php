<a
    {{ $attributes->merge([
        'class' =>
            'inline font-medium underline-offset-[6px] hover:decoration-current underline text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] text-sm text-base-content',
    ]) }}>{{ $slot }}</a>
