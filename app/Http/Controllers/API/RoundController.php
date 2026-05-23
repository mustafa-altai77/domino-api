<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoundResource;
use App\Models\Round;
use App\Models\Game;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Round::with(['winner', 'addedBy']);

        // Filter by game_id if provided
        if ($request->has('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        // Filter by date range
        $filter = $request->query('filter', 'all');

        switch ($filter) {
            case 'today':
                $query->whereDate('played_at', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('played_at', Carbon::yesterday());
                break;
            case 'week':
                $query->whereBetween('played_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'all':
            default:
                // No date filter
                break;
        }

        $rounds = $query->get();

        return RoundResource::collection($rounds);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'team1_score' => 'required|integer|min:0',
            'team2_score' => 'required|integer|min:0',
            'winner_id' => 'nullable|exists:teams,id',
            'played_at' => 'nullable|date',
        ]);

        $validated['added_by_user_id'] = auth()->id();

        // Set default played_at to now if not provided
        if (!isset($validated['played_at']) || !$validated['played_at']) {
            $validated['played_at'] = now();
        }

        $round = Round::create($validated);

        return new RoundResource($round->load(['winner', 'addedBy']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Round $round)
    {
        $validated = $request->validate([
            'team1_score' => 'sometimes|integer|min:0',
            'team2_score' => 'sometimes|integer|min:0',
            'winner_id' => 'sometimes|exists:teams,id',
            'played_at' => 'sometimes|date',
        ]);

        $round->update($validated);

        return new RoundResource($round->load(['winner', 'addedBy']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Round $round)
    {
        $round->delete();

        return response()->json([
            'message' => 'Round deleted successfully',
        ], 200);
    }

    /**
     * Get all rounds for a specific game.
     */
    public function gameRounds(Game $game)
    {
        $rounds = $game->rounds()->with(['winner', 'addedBy'])->get();
        return RoundResource::collection($rounds);
    }
}
