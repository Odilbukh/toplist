<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTopResultRequest;
use App\Http\Requests\SaveResultRequest;
use App\Http\Resources\GameResource;
use App\Models\Member;
use App\Models\Result;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    final public function saveResult(SaveResultRequest $request): void
    {
        $input = $request->validated();

        if ($request->has('email')) {
            $member = Member::where('email', $input['email'])->first();
        }

        Result::create([
            'member_id' => $member ? $member->id : null,
            'milliseconds' => $input['milliseconds'],
        ]);
    }

    final public function getTopResults(GetTopResultRequest $request): JsonResponse
    {
        $input = $request->validated();

        $query = Result::whereNotNull('member_id')
            ->with('member')
            ->groupBy('member_id')
            ->selectRaw('member_id, MIN(milliseconds) as milliseconds');

        if (array_key_exists('email', $input)) {
            $member = Member::where('email', $input['email'])->first();
            if ($member) {
                $selfResult = $member->results()->orderBy('milliseconds')->first();
            }
        }

        $topResults = $query->orderBy('milliseconds')->take(10)->get();

        $formattedTopResults = $topResults->map(function ($result, $index) {
            return [
                'email' => $result->member->hidden_email,
                'place' => $index + 1,
                'milliseconds' => $result->milliseconds,
            ];
        });

        $formattedSelfResult = isset($selfResult) ? new GameResource($selfResult, 10, true) : null;

        return response()->json([
            'data' => [
                'top' => $formattedTopResults,
                'self' => $formattedSelfResult,
            ],
        ]);
    }
}
