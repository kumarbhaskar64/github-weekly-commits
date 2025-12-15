<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\GitHubCommitsService;


class CommitsController extends Controller
{
    public function __construct(public readonly GitHubCommitsService $service){

    }

    public function weekly(string $user, string $repository, Request $request):JsonResponse {
        $since = $request->query('since'); // YYYY-MM-DD or null
        $until = $request->query('until');

        $data = $this->service->getWeeklyCommits($user, $repository, $since, $until);

        return response()->json($data);

    }
}
