@props(['title', 'description'])

<div class="flex w-full flex-col text-center">
    <x-text.heading size="xl">{{ $title }}</x-text.heading>
    <x-text.subheading>{{ $description }}</x-text.subheading>
</div>
