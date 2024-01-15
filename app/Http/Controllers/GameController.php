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
    final public function saveResult(SaveResultRequest $request): JsonResponse
    {
        $input = $request->validated();

        if ($request->has('email')) {
            $member = Member::where('email', $input['email'])->first();
        }

        $result = Result::create([
            'member_id' => $member ? $member->id : null,
            'milliseconds' => $input['milliseconds'],
        ]);

        if ($result) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    final public function getTopResults(GetTopResultRequest $request): JsonResponse
    {
        $input = $request->validated();

        $query = Result::whereNotNull('member_id')
            ->with('member')
            ->groupBy('member_id')
            ->selectRaw('member_id, MIN(milliseconds) as milliseconds');

        if (array_key_exists('email', $input)) {
            $query->whereHas('member', function ($query) use ($input) {
                $query->where('email', $input['email']);
            });
        }

        $topResults = $query->orderBy('milliseconds')->take(10)->get();
        $sortedResults = $topResults->sortBy('milliseconds');

        $sortedResults->each(function ($result, $index) {
            $result->place = $index + 1;
        });

        $selfResult = null;

        if (array_key_exists('email', $input)) {
            $member = Member::where('email', $input['email'])->first();

            if ($member) {
                $selfResult = $member->results()->orderBy('milliseconds')->first();
                $selfResult->place = $sortedResults->search(function ($item) use ($selfResult) {
                        return $item->id === $selfResult->id;
                    }) + 1;
            }
        }

        $formattedTopResults = GameResource::collection($sortedResults);

        $formattedSelfResult = $selfResult ? new GameResource($selfResult) : null;

        return response()->json([
            'data' => [
                'top' => $formattedTopResults,
                'self' => $formattedSelfResult,
            ],
        ]);
    }
}
