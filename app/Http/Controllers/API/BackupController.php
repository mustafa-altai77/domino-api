<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Round;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    /**
     * Export all data as JSON backup.
     */
    public function export()
    {
        $backup = [
            'teams' => Team::all()->toArray(),
            'games' => Game::all()->toArray(),
            'rounds' => Round::all()->toArray(),
        ];

        return response()->json($backup, 200);
    }

    /**
     * Import data from JSON backup.
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'backup' => 'required|json',
        ]);

        $backup = json_decode($validated['backup'], true);

        DB::beginTransaction();

        try {
            // Delete all existing data
            Round::query()->delete();
            Game::query()->delete();
            Team::query()->delete();

            // Insert teams with preserved IDs
            if (isset($backup['teams']) && is_array($backup['teams'])) {
                foreach ($backup['teams'] as $team) {
                    DB::table('teams')->insert([
                        'id' => $team['id'],
                        'name' => $team['name'],
                        'created_at' => $team['created_at'],
                        'updated_at' => $team['updated_at'],
                    ]);
                }
            }

            // Insert games with preserved IDs
            if (isset($backup['games']) && is_array($backup['games'])) {
                foreach ($backup['games'] as $game) {
                    DB::table('games')->insert([
                        'id' => $game['id'],
                        'target' => $game['target'],
                        'team1_id' => $game['team1_id'],
                        'team2_id' => $game['team2_id'],
                        'winner_id' => $game['winner_id'] ?? null,
                        'started_at' => $game['started_at'] ?? null,
                        'ended_at' => $game['ended_at'] ?? null,
                        'created_at' => $game['created_at'],
                        'updated_at' => $game['updated_at'],
                    ]);
                }
            }

            // Insert rounds with preserved IDs
            if (isset($backup['rounds']) && is_array($backup['rounds'])) {
                foreach ($backup['rounds'] as $round) {
                    DB::table('rounds')->insert([
                        'id' => $round['id'],
                        'game_id' => $round['game_id'],
                        'team1_score' => $round['team1_score'],
                        'team2_score' => $round['team2_score'],
                        'winner_id' => $round['winner_id'],
                        'added_by_user_id' => $round['added_by_user_id'],
                        'played_at' => $round['played_at'],
                        'created_at' => $round['created_at'],
                        'updated_at' => $round['updated_at'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Backup imported successfully',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to import backup',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
