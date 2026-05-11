<x-layouts.app :title="__('Pending invitations')">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings.teams.members.index', $team)">{{ __('Members of :team', ['team' => $team->name]) }}</x-back-link>
        @endunlesshotwirenative

        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                @unlesshotwirenative
                <x-text.heading size="xl">{{ __('Pending invitations') }}</x-text.heading>
                @endunlesshotwirenative
                <x-text.subheading>{{ __('Invitations that have not been accepted yet.') }}</x-text.subheading>
            </div>

            <div class="flex items-center">
                @can('addInvitation', $team)
                    <a class="btn btn-sm btn-neutral" href="{{ route('settings.teams.invitations.create', $team) }}">
                        <x-dynamic-component component="heroicon-o-user-plus" class="size-4" aria-hidden="true" />
                        <span>{{ __('Invite member') }}</span>
                    </a>
                @endcan
            </div>
        </div>

        <x-page-card class="my-6 p-0!">
            <ul class="list p-0 m-0">
                @forelse ($invitations as $invitation)
                    <li class="list-row">
                        <div class="size-10 flex items-center justify-center rounded-full bg-neutral/70 text-neutral-content">
                            <x-dynamic-component component="heroicon-o-envelope" class="size-4" aria-hidden="true" />
                        </div>

                        <div>
                            <div>{{ $invitation->email }}</div>
                            <div class="text-xs uppercase font-semibold opacity-60">{{ $invitation->role->label() }}</div>
                        </div>

                        @can('cancelInvitation', $team)
                        <form action="{{ route('settings.teams.invitations.destroy', ['team' => $team, 'invitation' => $invitation]) }}" method="post" class="block" data-turbo-confirm="{{ __('Are you sure you want to delete this invitation?') }}">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-square btn-ghost">
                                <x-dynamic-component component="heroicon-o-trash" class="size-[1.2em]" aria-hidden="true" />
                                <span class="sr-only">{{ __('Delete') }}</span>
                            </button>
                        </form>
                        @endcan
                    </li>
                @empty
                    <li class="px-4 py-6">
                        <p class="text-center text-sm">{{ __('No pending invitations.') }}</p>
                    </li>
                @endforelse
            </ul>
        </x-page-card>
    </section>
</x-layouts.app>
