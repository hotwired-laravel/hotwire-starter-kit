<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TeamSwitchController extends Controller
{
    use AuthorizesRequests;

    public function update(Request $request, Team $team)
    {
        $this->authorize('view', $team);

        $currentTeamSlug = $request->user()->currentTeam?->slug;

        $request->user()->switchTeam($team);

        if (! $request->header('Referer') || $request->boolean('to_dashboard')) {
            return to_route('dashboard', ['current_team' => $team->slug]);
        }

        if (! $currentTeamSlug) {
            return redirect($request->header('Referer'));
        }

        $redirectTo = $this->replaceCurrentTeamInReferer($request->header('Referer'), $currentTeamSlug, $team->slug);

        return redirect($redirectTo ?? request()->header('Referer'));
    }

    private function replaceCurrentTeamInReferer(string $referer, string $currentTeamSlug, string $newTeamSlug): ?string
    {
        $redirectTo = preg_replace(
            '#/'.preg_quote($currentTeamSlug, '#').'(?=/|\?|$)#',
            '/'.$newTeamSlug,
            $referer,
            1,
        );

        return preg_replace(
            '#([?&]current_team=)'.preg_quote($currentTeamSlug, '#').'(?=&|$)#',
            '$1'.$newTeamSlug,
            $redirectTo ?? $referer,
            1,
        );
    }
}
