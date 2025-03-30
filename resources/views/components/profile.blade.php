@props(['initials' => null, 'name' => null, 'avatar' => null])

<div
    {{ $attributes->merge(['class' => 'group flex items-center rounded-lg has-data-[circle=true]:rounded-full p-1 hover:bg-base-300']) }}>
    <div class="shrink-0">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 0 512 512" class="size-8" aria-hidden="true">
            <defs>
                <clipPath id="porthole">
                    <circle cx="50%" cy="50%" r="50%" />
                </clipPath>
            </defs>

            <g>
                <rect width="100%" height="100%" rx="50" fill="currentColor" class="text-accent-content" />

                <text x="50%" y="50%" fill="currentColor" class="text-accent" text-anchor="middle" dy="0.35em"
                    font-family="-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif"
                    font-size="230" font-weight="800" letter-spacing="-5"
                    @if (strlen($initials ?? '') >= 3 ?? false) text-length="85%" length-adjust="spacingAndGlyphs" @endif>
                    {{ $initials }}
                </text>
            </g>
        </svg>
    </div>

    @if ($name)
        <span class="mx-2 text-sm text-base-200 group-hover:text-base-300 font-medium truncate">
            {{ $name }}
        </span>
    @endif
</div>
