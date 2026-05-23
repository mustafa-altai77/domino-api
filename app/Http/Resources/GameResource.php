<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $scores = $this->getCurrentScores();

        return [
            'id' => $this->id,
            'target' => $this->target,
            'targetScore' => $this->target,
            'team1_id' => $this->team1_id,
            'team2_id' => $this->team2_id,
            'team1_score' => $scores['team1_score'],
            'team2_score' => $scores['team2_score'],
            'winner_id' => $this->winner_id,
            'status' => $this->winner_id ? 'ended' : 'active',
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'created_at' => $this->created_at,
            'team1' => new TeamResource($this->whenLoaded('team1')),
            'team2' => new TeamResource($this->whenLoaded('team2')),
            'winner' => new TeamResource($this->whenLoaded('winner')),
        ];
    }
}
