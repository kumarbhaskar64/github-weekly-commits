<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

final class GitHubCommitsService
{
    public function getWeeklyCommits(string $user, string $repo, CarbonImmutable $sinceDt, CarbonImmutable $untilDt,int $maxPages): array {
        $sinceIso = $sinceDt->utc()->toIso8601String();
        $untilIso = $untilDt->utc()->toIso8601String();

        return $this->fetchWeeklyCommits(user: $user, repo: $repo,sinceDt: $sinceDt, sinceIso: $sinceIso,untilIso: $untilIso,maxPages: $maxPages);
    }


    //Function handle request to make Http request to get the Github commit data
    private function fetchWeeklyCommits(string $user,string $repo, CarbonImmutable $sinceDt,string $sinceIso, string $untilIso,int $maxPages): array {
        
        $url = "https://api.github.com/repos/{$user}/{$repo}/commits";

        $page = 1;
        $perPage = 100;

        $byWeek = [];
        $truncated = false;
        $lastSeenDt = null;

        while ($page <= $maxPages) {

            $resp = Http::withHeaders([
                    'Accept' => 'application/vnd.github+json',
                    'User-Agent' => 'laravel-github-commits-weekly',
                ])
                ->when(config('services.github.token'), fn ($h) =>
                    $h->withToken(config('services.github.token'))
                )
                ->timeout(15)
                ->get($url, [
                    'since' => $sinceIso,
                    'until' => $untilIso,
                    'per_page' => $perPage,
                    'page' => $page,
                ]);

            /* ================= Rate limit check starts ================= */
            if ($resp->status() === 403) {
                $remaining = $resp->header('X-RateLimit-Remaining');
                $reset = $resp->header('X-RateLimit-Reset');

                if ($remaining !== null && (int)$remaining === 0) {
                    $resetAt = $reset
                        ? CarbonImmutable::createFromTimestamp((int)$reset)->toIso8601String()
                        : 'unknown time';

                    throw new \RuntimeException(
                        "GitHub rate limit exceeded. Try again after {$resetAt}.",
                        429
                    );
                }
            }
            /* =================  Rate limits check ends ======================= */

            if (!$resp->successful()) {
                throw new \RuntimeException(
                    "GitHub API error ({$resp->status()}): " . $resp->body()
                );
            }

            $items = $resp->json();
            if (empty($items)) break;

            foreach ($items as $commit) {
                $dateStr = data_get($commit, 'commit.author.date');
                if (!$dateStr) continue;

                $dt = CarbonImmutable::parse($dateStr);
                $lastSeenDt = $dt;

                //Skip older commits than 
                if ($dt->lt($sinceDt)) {
                    break 2;
                }

                //$isoYear = (int) $dt->format('o');
				//$isoWeek = (int) $dt->format('W');

                $key = ((int)$dt->format('o')) * 100 + (int)$dt->format('W');

                $byWeek[$key] ??= [
                    'year' => (int)$dt->format('o'),
                    'week' => (int)$dt->format('W'),
                    'count' => 0,
                    'commits' => [],
                ];

                $byWeek[$key]['commits'][] = [
                    'sha' => $commit['sha'] ?? null,
                    'date' => $dateStr,
                    'message' => data_get($commit, 'commit.message'),
                    'author' => data_get($commit, 'commit.author.name'),
                ];

                $byWeek[$key]['count']++;
            }

            if (count($items) < $perPage) break;

            $page++;
        }

        //Marking flag truncxated to true to get inform about partial data
        if ($page > $maxPages && $lastSeenDt) {
            $truncated = true;
        }

        ksort($byWeek);

        return [
            'meta' => [
                'user' => $user,
                'repo' => $repo,
                'since_iso' => $sinceIso,
                'until_iso' => $untilIso,
                'max_pages' => $maxPages,
                'truncated' => $truncated,
                'next' => $truncated && $lastSeenDt ? ['until' => $lastSeenDt->utc()->toIso8601String()] : null,
            ],
            'data' => array_values($byWeek),
        ];
    }


}
