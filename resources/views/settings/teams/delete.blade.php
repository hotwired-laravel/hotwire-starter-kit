<x-layouts.app :title="__('Delete team')">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings.teams.show', $team)">{{ $team->name }}</x-back-link>
        <x-text.heading size="xl">{{ __('Delete team') }}</x-text.heading>
        @endunlesshotwirenative
        <x-text.subheading>{{ __('Delete this team and all of its memberships and pending invitations') }}</x-text.subheading>

        <x-page-card class="my-6">
            <p class="text-sm">{{ __('Once a team is deleted, all of its memberships and pending invitations will be removed. Type "delete" below to confirm you would like to permanently delete this team.') }}</p>

            <form action="{{ route('settings.teams.destroy', $team) }}" method="post" class="mt-6 w-full space-y-6" data-controller="bridge--form" data-action="turbo:submit-start->bridge--form#submitStart turbo:submit-end->bridge--form#submitEnd">
                @csrf

                <div>
                    <x-form.label for="confirmation">{{ __('Type "delete" to confirm') }}</x-form.label>

                    <x-form.text-input
                        id="confirmation"
                        name="confirmation"
                        :value="old('confirmation', '')"
                        :data-error="$errors->has('confirmation')"
                        required
                        autofocus
                        autocomplete="off"
                        :placeholder="__('delete')"
                        class="mt-2"
                    />

                    <x-form.error :message="$errors->first('confirmation')" />
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <x-form.button.danger type="submit" class="w-full" data-bridge--form-target="submit" data-bridge-title="{{ __('Delete') }}" data-bridge-destructive="true">{{ __('Delete team') }}</x-form.button.danger>
                    </div>
                </div>
            </form>
        </x-page-card>
    </section>
</x-layouts.app>
