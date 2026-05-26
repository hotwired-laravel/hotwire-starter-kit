<?php

namespace App\Http\Controllers\Settings;

use App\Enums\TeamRole;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamMembersController extends Controller
{
    use AuthorizesRequests;

    public function index(Team $team)
    {
        $this->authorize('view', $team);

        return view('settings.team-members.index', [
            'team' => $team->loadCount(['invitations' => fn ($query) => $query->pending()]),
            'members' => $team->members()->orderBy('name', 'asc')->get(),
        ]);
    }

    public function update(Request $request, Team $team, User $member)
    {
        $this->authorize('updateMember', $team);

        $validated = $request->validate([
            'role' => ['required', 'string', Rule::enum(TeamRole::class)->except(TeamRole::Owner)],
        ]);

        $membership = $team->memberships()
            ->where('user_id', $member->getKey())
            ->firstOrFail();

        abort_if($membership->role === TeamRole::Owner, 403);

        $membership->update(['role' => TeamRole::from($validated['role'])]);

        return back()->with('notice', __('Member role updated.'));
    }

    public function destroy(Request $request, Team $team, User $member)
    {
        $this->authorize('removeMember', $team);

        $membership = $team->memberships()
            ->where('user_id', $member->getKey())
            ->firstOrFail();

        abort_if($membership->role === TeamRole::Owner, 403);
        abort_if($member->is($request->user()), 403);

        $membership->delete();

        return back()->with('notice', __('Member removed.'));
    }
}
