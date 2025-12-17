<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\GitHubCommitsService;
use App\Http\Requests\WeeklyCommitsRequest;

class CommitsController extends Controller
{
    public function __construct(private readonly GitHubCommitsService $service) {}

    public function weekly( string $user, string $repository, WeeklyCommitsRequest $request): JsonResponse {
        
        $request->ensureRangeAllowed();

        try {
            $resultData = $this->service->getWeeklyCommits(user: $user, repo: $repository,  sinceDt: $request->sinceDt(),
                                                        untilDt: $request->untilDt(),  maxPages: $request->maxPages() );
        } catch (\RuntimeException $e) {
            
            // If rate limit issue occurs
            if ($e->getCode() === 429) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], 429);
            }
            throw $e;
        }

        return response()->json( $resultData,  $resultData['meta']['truncated'] ? 206 : 200);
    }
}