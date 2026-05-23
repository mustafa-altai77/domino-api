<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $game = $this->game()->first();
        $team1 = $game ? $game->team1()->first() : null;
        $team2 = $game ? $game->team2()->first() : null;
        $addedBy = $this->addedBy;

        return [
            'id' => $this->id,
            'gameId' => $this->game_id,
            'team1Score' => $this->team1_score,
            'team2Score' => $this->team2_score,
            'team1Name' => $team1?->name ?? 'Team 1',
            'team2Name' => $team2?->name ?? 'Team 2',
            'winnerId' => $this->winner_id,
            'playedAt' => $this->played_at,
            'createdAt' => $this->created_at,
            'addedByName' => $addedBy?->name ?? 'Unknown',
            'winner' => new TeamResource($this->whenLoaded('winner')),
            'addedBy' => $this->whenLoaded('addedBy', function () {
                return [
                    'id' => $this->addedBy->id,
                    'name' => $this->addedBy->name,
                ];
            }),
        ];
    }
}
