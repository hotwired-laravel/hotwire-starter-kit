<x-layouts.app :title="__('Create Team')">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings.teams.index')">{{ __('My teams') }}</x-back-link>
        <x-text.heading size="xl">{{ __('Create Team') }}</x-text.heading>
        @endunlesshotwirenative
        <x-text.subheading>{{ __('Give your team a name to get started.') }}</x-text.subheading>

        <x-page-card class="my-6">
            <form action="{{ route('settings.teams.store') }}" method="post" class="w-full space-y-6" data-controller="bridge--form" data-action="turbo:submit-start->bridge--form#submitStart turbo:submit-end->bridge--form#submitEnd">
                @csrf

                <!-- Team Name -->
                <div>
                    <x-form.label for="name">{{ __('Team Name') }}</x-form.label>

                    <x-form.text-input
                        id="team_name"
                        name="team_name"
                        :value="old('team_name', '')"
                        :data-error="$errors->has('team_name')"
                        required
                        autofocus
                        autocomplete="team_name"
                        :placeholder="__('Team name')"
                        class="mt-2"
                    />

                    <x-form.error :message="$errors->first('team_name')" />
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <x-form.button.primary type="submit" class="w-full" data-bridge--form-target="submit" data-bridge-title="{{ __('Create') }}">{{ __('Create') }}</x-form.button.primary>
                    </div>
                </div>
            </form>
        </x-page-card>
    </section>
</x-layouts.app>
