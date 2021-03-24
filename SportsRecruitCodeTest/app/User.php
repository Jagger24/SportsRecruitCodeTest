<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class User extends Model
{
    public $timestamps = false;

    /**
     * Get Players sorted by rank
     */
    public static function getPlayersGroupedByRank()
    {
      #do this in random order to help the randomness of teams after the order bys
        return self::where('user_type', 'player')
                      ->orderBy('can_play_goalie', 'DESC')
                      ->orderBy('ranking', 'DESC')
                      ->inRandomOrder()
                      ->get();
    }

    #assign the relationship to a team
    public function team()
    {
        return $this->belongsTo(Teams::class);
    }

    #add the user to a team
    public function addTeam($team_id)
    {
      $this->team_id = $team_id;
      $this->save();
    }

    /**
     * Players only local scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfPlayers($query): Builder
    {
        return $query->where('user_type', 'player');
    }

    public function getIsGoalieAttribute(): bool
    {
        return (bool) $this->can_play_goalie;
    }

    public function getFullnameAttribute(): string
    {
        return Str::title($this->first_name . ' ' . $this->last_name);
    }

}
