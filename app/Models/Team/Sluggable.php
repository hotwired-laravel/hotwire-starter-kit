<?php

namespace App\Models\Team;

use App\Models\Team;
use Illuminate\Support\Str;

trait Sluggable
{
    /**
     * Bootstrap the sluggable concern behavior for the Team model.
     */
    protected static function bootSluggable(): void
    {
        static::whenBooted(static function (): void {
            static::creating(static function (Team $team): void {
                $team->slug ??= $team->generateUniqueTeamSlug();
            });

            static::updating(static function (Team $team): void {
                if ($team->isDirty('name')) {
                    $team->slug = $team->generateUniqueTeamSlug();
                }
            });
        });
    }

    /**
     * Generate a unique slug for the team.
     */
    protected function generateUniqueTeamSlug(): string
    {
        $defaultSlug = Str::slug($this->name);

        $existingSlugs = static::withTrashed()
            ->where(function ($query) use ($defaultSlug) {
                $query->where('slug', $defaultSlug)
                    ->orWhere('slug', 'like', $defaultSlug.'-%');
            })
            ->when($this->exists, function ($query) {
                $query->where('id', '!=', $this->getKey());
            })
            ->pluck('slug');

        $maxSuffix = $existingSlugs
            ->map(function (string $slug) use ($defaultSlug): ?int {
                if ($slug === $defaultSlug) {
                    return 0;
                } elseif (preg_match('/^'.preg_quote($defaultSlug, '/').'-(\d+)$/', $slug, $matches)) {
                    return (int) $matches[1];
                }

                return null;
            })
            ->filter(fn (?int $suffix) => $suffix !== null)
            ->max() ?? 0;

        return $existingSlugs->isEmpty()
            ? $defaultSlug
            : $defaultSlug.'-'.($maxSuffix + 1);
    }
}
