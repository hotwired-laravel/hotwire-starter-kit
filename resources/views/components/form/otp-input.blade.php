@props([
    'digits' => 6,
    'name' => 'code',
])

<div
    data-controller="otp"
    data-otp-digits-value="@js($digits)"
    data-action="focus-auth-2fa-auth-code@window->otp#focusFirstInputField clear-auth-2fa-auth-code@window->otp#clearAllInputs"
    class="relative"
>
    <div class="flex items-center justify-center space-x-2">
        @for ($x = 0; $x < $digits; $x++)
            <input
                data-otp-target="input"
                type="text"
                inputmode="numeric"
                pattern="[0-9]"
                maxlength="1"
                autocomplete="off"
                data-action="paste->otp#handlePaste keydown->otp#handleKeydown focus->otp#handleFocus input->otp#sanitizeInput"
                class="h-10 w-10 input text-center text-sm font-medium @if($x == 0) rounded-l-md @endif @if($x == $digits - 1) rounded-r-md @endif @if($x > 0) -ml-px @endif" />
        @endfor
    </div>

    <input {{ $attributes->except(['digits'])->merge([
        'name' => $name,
        'data-otp-target' => 'code',
        'type' => 'hidden',
        'class' => 'hidden',
        'minlength' => $digits,
        'maxlength' => $digits,
    ]) }} />
</div>
