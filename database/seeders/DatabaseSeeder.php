<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Game;
use App\Models\Round;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@domino.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create normal user
        $player = User::create([
            'name' => 'Player',
            'email' => 'player@domino.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create teams
        $team1 = Team::create(['name' => 'Team 1']);
        $team2 = Team::create(['name' => 'Team 2']);

        // Create a game
        $game = Game::create([
            'target' => 51,
            'team1_id' => $team1->id,
            'team2_id' => $team2->id,
            'started_at' => now(),
        ]);

        // Create a sample round
        Round::create([
            'game_id' => $game->id,
            'team1_score' => 20,
            'team2_score' => 15,
            'winner_id' => $team1->id,
            'added_by_user_id' => $admin->id,
            'played_at' => now(),
        ]);
    }
}
