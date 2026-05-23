<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['target', 'team1_id', 'team2_id', 'winner_id', 'started_at', 'ended_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner_id');
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function getCurrentScores()
    {
        $latestRound = $this->rounds()
            ->latest('id')
            ->first();

        if (!$latestRound) {
            return [
                'team1_score' => 0,
                'team2_score' => 0,
            ];
        }

        return [
            'team1_score' => $latestRound->team1_score,
            'team2_score' => $latestRound->team2_score,
        ];
    }
}
