<nav {{ $attributes->merge(['class' => 'menu w-full group-has-[:checked]/sidebar:lg:w-auto space-y-0.5']) }}>
    {{ $slot }}
</nav>
