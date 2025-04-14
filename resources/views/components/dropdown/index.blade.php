<details {{ $attributes->merge(['class' => 'dropdown']) }}>
    <summary {{ $trigger->attributes->merge(['class' => 'btn m-1']) }}>
        {{ $trigger }}
    </summary>

    {{ $slot }}
</details>
