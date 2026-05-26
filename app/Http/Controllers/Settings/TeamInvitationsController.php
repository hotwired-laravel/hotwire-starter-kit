<?php

namespace App\Http\Controllers\Settings;

use App\Enums\TeamRole;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Notifications\Teams\TeamInvitation as TeamInvitationNotification;
use App\Rules\UniqueTeamInvitation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class TeamInvitationsController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;

    public static function middleware(): array
    {
        return [new Middleware('throttle:invitations', only: ['store'])];
    }

    public function index(Team $team)
    {
        $this->authorize('view', $team);

        return view('settings.team-invitations.index', [
            'team' => $team,
            'invitations' => $team->invitations()->pending()->get(),
        ]);
    }

    public function create(Team $team)
    {
        $this->authorize('addInvitation', $team);

        return view('settings.team-invitations.create', [
            'team' => $team,
        ]);
    }

    public function store(Request $request, Team $team)
    {
        $this->authorize('addInvitation', $team);

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', new UniqueTeamInvitation($team)],
            'role' => ['required', 'string', Rule::enum(TeamRole::class)],
        ]);

        $invitation = $team->invitations()->create([
            'email' => $validated['email'],
            'role' => TeamRole::from($validated['role']),
            'invited_by' => $request->user()->getKey(),
            'expires_at' => now()->addDays(3),
        ]);

        Notification::route('mail', $invitation->email)->notify(new TeamInvitationNotification($invitation));

        return to_route('settings.teams.invitations.index', $team)->with('notice', 'Invitation sent.');
    }

    public function destroy(Team $team, TeamInvitation $invitation)
    {
        $this->authorize('cancelInvitation', $team);

        $invitation->delete();

        return to_route('settings.teams.invitations.index', $team)->with('notice', __('Invitation cancelled.'));
    }
}
