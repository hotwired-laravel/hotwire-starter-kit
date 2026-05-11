<x-layouts.app :title="__('Team Members')">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings.teams.show', $team)">{{ $team->name }}</x-back-link>
        @endunlesshotwirenative

        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                @unlesshotwirenative
                <x-text.heading size="xl">{{ __('Team Members') }}</x-text.heading>
                @endunlesshotwirenative
                <x-text.subheading>{{ __('Manage your team memberships.') }}</x-text.subheading>
            </div>

            <div class="flex items-center">
                <a class="btn btn-sm btn-link" href="{{ route('settings.teams.invitations.index', $team) }}">
                    <span>{{ trans_choice(':count pending invitation|:count pending invitations', $team->invitations_count) }}</span>
                </a>
            </div>
        </div>

        <x-page-card class="my-6 p-0!">
            <ul class="list p-0 m-0">
                @foreach ($members as $member)
                    <li class="list-row">
                        <x-profile
                            :initials="$member->initials()"
                            :as-link="false"
                        />

                        <div>
                            <div>{{ $member->name }}</div>
                            <div class="text-xs font-semibold opacity-60">{{ $member->email }}</div>
                        </div>

                        <div class="flex items-center">
                        @if ($member->is(auth()->user()) || auth()->user()->cannot('updateMember', $team) || $member->membership->role === App\Enums\TeamRole::Owner)
                            <div class="text-xs uppercase font-semibold opacity-60">{{ $member->membership->role->label() }}</div>
                        @else
                            <form
                                class="block"
                                action="{{ route('settings.teams.members.update', [$team, $member]) }}"
                                method="post"
                                data-controller="autosave"
                                data-turbo-confirm="{{ __('Are you sure you want to update the role?') }}"
                            >
                                @csrf
                                @method('PUT')

                                <select class="select select-sm" name="role" data-action="autosave#saveAutomatically">
                                    <optgroup label="{{ __('Roles') }}">
                                        @foreach (App\Enums\TeamRole::cases() as $role)
                                        @continue($role === App\Enums\TeamRole::Owner)
                                        <option value="{{ $role->value }}" @if ($member->membership->role === $role) selected @endif >{{ $role->label() }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>

                                <noscript><button type="submit">{{ __('Save') }}</button></noscript>

                                <button class="sr-only">{{ __('Save') }}</button>
                            </form>
                        @endif
                        </div>

                        @if(auth()->user()->can('removeMember', $team) && auth()->user()->isNot($member))
                            <form
                                class="block"
                                action="{{ route('settings.teams.members.destroy', [$team, $member]) }}"
                                method="post"
                                data-turbo-confirm="{{ __('Are you sure you want to remove this member?') }}"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-base btn-square btn-ghost">
                                    <x-dynamic-component component="heroicon-o-trash" class="size-4" aria-hidden="true" />
                                    <span class="sr-only">{{ __('Delete') }}</span>
                                </button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        </x-page-card>
    </section>
</x-layouts.app>
