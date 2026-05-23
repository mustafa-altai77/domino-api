<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::with(['team1', 'team2', 'winner'])->get();
        return GameResource::collection($games);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'target' => 'required|integer|min:1',
            'team1_id' => 'required|exists:teams,id',
            'team2_id' => 'required|exists:teams,id|different:team1_id',
        ]);

        $validated['started_at'] = now();

        $game = Game::create($validated);

        return new GameResource($game->load(['team1', 'team2']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        return new GameResource($game->load(['team1', 'team2', 'winner']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'winner_id' => 'required|exists:teams,id',
        ]);

        $validated['ended_at'] = now();

        $game->update($validated);

        return new GameResource($game->load(['team1', 'team2', 'winner']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
