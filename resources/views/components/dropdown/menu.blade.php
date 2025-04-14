@props(['bgColor' => 'bg-base-100'])
<ul {{ $attributes->merge(['class' => 'menu dropdown-content rounded-box z-1 w-52 p-2 shadow ' . $bgColor]) }}>
    {{ $slot }}
</ul>
