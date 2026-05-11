<?php

namespace App\Http\Controllers;

use App\Models\TeamInvitation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AcceptedInvitationsController extends Controller
{
    use AuthorizesRequests;

    public function show(Request $request, TeamInvitation $invitation)
    {
        $this->guardAgainstInvalidInvitations($user = $request->user(), $invitation);

        DB::transaction(function () use ($invitation, $user) {
            $team = $invitation->team;

            $team->memberships()->firstOrCreate(
                ['user_id' => $user->id],
                ['role' => $invitation->role]
            );

            $invitation->update(['accepted_at' => now()]);

            $user->switchTeam($team);
        });

        return to_route('dashboard')->with('notice', __('Joined :team', ['team' => $invitation->team->name]));
    }

    private function guardAgainstInvalidInvitations($user, TeamInvitation $invitation): void
    {
        if ($invitation->isAccepted()) {
            throw ValidationException::withMessages([
                'invitation' => [__('This invitation has already been accepted.')],
            ]);
        }

        if ($invitation->isExpired()) {
            throw ValidationException::withMessages([
                'invitation' => [__('This invitation has expired.')],
            ]);
        }

        if (Str::lower($invitation->email) !== Str::lower($user->email)) {
            throw ValidationException::withMessages([
                'invitation' => [__('This invitation was sent to a different email address.')],
            ]);
        }
    }
}
