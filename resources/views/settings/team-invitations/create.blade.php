<x-layouts.app :title="__('Invite member to :team', ['team' => $team->name])">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings.teams.invitations.index', $team)">{{ __('Pending invitations of :team', ['team' =>$team->name]) }}</x-back-link>
        <x-text.heading size="xl">{{ __('Invite member to :team', ['team' => $team->name]) }}</x-text.heading>
        @endunlesshotwirenative
        <x-text.subheading>{{ __('Send an invitation to join this team.') }}</x-text.subheading>

        <x-page-card class="my-6">
            <form action="{{ route('settings.teams.invitations.store', $team) }}" method="post" class="w-full space-y-6" data-controller="bridge--form" data-action="turbo:submit-start->bridge--form#submitStart turbo:submit-end->bridge--form#submitEnd">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-form.label for="email">{{ __('Email address') }}</x-form.label>

                    <x-form.text-input
                        id="email"
                        name="email"
                        type="email"
                        :value="old('email', '')"
                        :data-error="$errors->has('email')"
                        required
                        autocomplete="email"
                        :placeholder="__('email@example.com')"
                        class="mt-2"
                    />

                    <x-form.error :message="$errors->first('email')" />
                </div>

                <!-- Role -->
                <div>
                    <x-form.label for="role">{{ __('Role') }}</x-form.label>

                    <div>
                        <select id="role" name="role" class="select w-full mt-2">
                            @foreach (App\Enums\TeamRole::cases() as $role)
                            @continue($role === App\Enums\TeamRole::Owner)
                            <option value="{{ $role->value }}">{{ $role->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-form.error :message="$errors->first('role')" />
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
