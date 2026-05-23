<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = ['game_id', 'team1_score', 'team2_score', 'winner_id', 'added_by_user_id', 'played_at'];

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }
}
