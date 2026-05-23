<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name'];

    public function games1()
    {
        return $this->hasMany(Game::class, 'team1_id');
    }

    public function games2()
    {
        return $this->hasMany(Game::class, 'team2_id');
    }

    public function roundsWon()
    {
        return $this->hasMany(Round::class, 'winner_id');
    }
}
