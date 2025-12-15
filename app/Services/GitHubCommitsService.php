<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

final class GitHubCommitsService
{	
	/**
     * @return array<int, array{week:int, count:int, commits:array}>
     */
	public function getWeeklyCommits(string $user, string $repo, ?string $since, ?string $until) : array
	{
		$sinceDt = $since 
					? CarbonImmutable::parse($since)->startOfDay()
					: CarbonImmutable::today()->subWeeks(4)->startOfDay();

		$untilDt = $until
					? CarbonImmutable::parse($until)->endOfDay()
					: CarbonImmutable::today()->endOfDay();

		$sinceIso = $sinceDt->utc()->toIso8601String();
		$untilIso = $untilDt->utc()->toIso8601String();

		$commits = $this->fetchAllCommits($user, $repo, $sinceIso, $untilIso);


		//Group by week
		$byWeek = [];  

		foreach ($commits as $commit) {
		    $dateStr = data_get($commit, 'commit.author.date');
		    if (!$dateStr){
		    	continue;
		    }

		    $week = (int) \Carbon\CarbonImmutable::parse($dateStr)->isoWeek();

		    $byWeek[$week] ??= ['week' => $week, 'count' => 0, 'commits' => []];
		    $byWeek[$week]['commits'][] = $commit;
		    $byWeek[$week]['count']++;
		}

		ksort($byWeek);
		return array_values($byWeek);

	}




	/**    
     * @return array<int, array>
     */
	private function fetchAllCommits(string $user, string $repo, string $sinceIso, string $untilIso):array 
	{
		$baseUrl = "https://api.github.com/repos/{$user}/{$repo}/commits";

		$page = 1;
		$perPage = 100;
		$all = [];

		while(true){
			$resp = Http::withHeaders([
						'Accept' => 'application/vnd.github+json',
						'User-Agent' => 'laravel-github-commits-weekly',
					])
					->timeout(15)
					->get($baseUrl,[
						'since' => $sinceIso,
						'until' => $untilIso,
						'per_page' => $perPage,
						'page' => $page,
					]);

		 
			if (!$resp->successful()) {
	            throw new \RuntimeException(
	                "GitHub API error ({$resp->status()}): " . $resp->body()
	            );
	        }

	        $items = $resp->json();
	        if (!is_array($items) || count($items) === 0) {
	            break;
	        }

	        $all = array_merge($all, $items);

	        if (count($items) < $perPage) {
	            break; // last page
	        }

	        $page++;
	        if ($page > 20) { // safety cap; document/adjust as needed
	            break;
	        }

		}

		return $all;
	}

}