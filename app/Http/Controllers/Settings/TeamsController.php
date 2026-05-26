<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Teams\CreateTeam;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Rules\TeamName;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeamsController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        return view('settings.teams.index', [
            'teams' => $request->user()->teams,
        ]);
    }

    public function create()
    {
        return view('settings.teams.create');
    }

    public function store(Request $request, CreateTeam $createTeam)
    {
        $validated = $request->validate([
            'team_name' => ['required', 'string', 'max:255', new TeamName],
        ]);

        $team = $createTeam->handle($request->user(), $validated['team_name']);

        return redirect()->route('settings.teams.show', $team)->with('notice', __('Team created.'));
    }

    public function show(Request $request, Team $team)
    {
        $this->authorize('view', $team);

        return view('settings.teams.show', [
            'team' => $team,
        ]);
    }

    public function edit(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        return view('settings.teams.edit', [
            'team' => $team,
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $validated = $request->validate(['team_name' => ['required', 'string', 'max:255', new TeamName]]);

        $team = DB::transaction(function () use ($team, $validated) {
            return tap(Team::lockForUpdate()->findOrFail($team->getKey()))->update([
                'name' => $validated['team_name'],
            ]);
        });

        return to_route('settings.teams.show', $team)->with('notice', __('Team name updated.'));
    }

    public function delete(Request $request, Team $team)
    {
        $this->authorize('delete', $team);

        return view('settings.teams.delete', [
            'team' => $team,
        ]);
    }

    public function destroy(Request $request, Team $team)
    {
        $this->authorize('delete', $team);

        $request->validate([
            'confirmation' => ['required', 'string', Rule::in(['delete'])],
        ], [
            'confirmation.in' => __('Type "delete" to confirm.'),
        ]);

        $user = $request->user();

        DB::transaction(function () use ($user, $team) {
            if ($user->isCurrentTeam($team)) {
                $fallback = $user->fallbackTeam(excluding: $team);

                if ($fallback) {
                    $user->switchTeam($fallback);
                }
            }

            $team->delete();
        });

        return to_route('settings.teams.index')->with('notice', __('Team deleted.'));
    }
}
