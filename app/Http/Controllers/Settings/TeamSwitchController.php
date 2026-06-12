<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamSwitchController extends Controller
{
    use AuthorizesRequests;

    public function update(Request $request, Team $team): RedirectResponse
    {
        $this->authorize('view', $team);

        $currentTeamSlug = $request->user()->currentTeam?->slug;

        $request->user()->switchTeam($team);

        $referer = $request->header('Referer');

        if (! is_string($referer) || $referer === '' || $request->boolean('to_dashboard')) {
            return to_route('dashboard', ['current_team' => $team->slug]);
        }

        if (! $currentTeamSlug) {
            return redirect($referer);
        }

        $redirectTo = $this->replaceCurrentTeamInReferer($referer, $currentTeamSlug, $team->slug);

        return redirect($redirectTo ?? $referer);
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
