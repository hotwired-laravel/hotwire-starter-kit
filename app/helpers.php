<?php

use App\Models\Team;

if (! function_exists('active_team')) {
    /**
     * Resolve the team being viewed in the current request.
     *
     * Prefers the `{current_team}` route slug (the canonical signal for
     * "active team this request") so that browsing to /team-b/... shows
     * team B regardless of the user's persisted `current_team_id`.
     * Falls back to the persisted current team for routes without the
     * prefix (e.g. /settings/...). Returns null for guests.
     */
    function active_team(): ?Team
    {
        $user = auth()->user();

        if (! $user) {
            return null;
        }

        $slug = request()->route('current_team');

        if (is_string($slug)) {
            $team = $user->teams->firstWhere('slug', $slug);

            if ($team) {
                return $team;
            }
        }

        return $user->currentTeam;
    }
}
