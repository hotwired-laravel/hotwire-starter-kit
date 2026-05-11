<x-layouts.app :title="__('Accept invitation to :team', ['team' => $team->name])">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-text.heading size="xl">{{ __('Accept invitation to :team', ['team' => $team->name]) }}</x-text.heading>
        @endunlesshotwirenative
        <x-text.subheading>{{ __('Accept the invitation to join the team.') }}</x-text.subheading>

        <x-page-card class="my-6">
            <form action="{{ route('accepted-invitations.store', $team) }}" method="post" class="w-full space-y-6" data-controller="bridge--form" data-action="turbo:submit-start->bridge--form#submitStart turbo:submit-end->bridge--form#submitEnd">
                @csrf

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <x-form.button.primary type="submit" class="w-full" data-bridge--form-target="submit" data-bridge-title="{{ __('Accept') }}">{{ __('Accept') }}</x-form.button.primary>
                    </div>
                </div>
            </form>
        </x-page-card>
    </section>
</x-layouts.app>
