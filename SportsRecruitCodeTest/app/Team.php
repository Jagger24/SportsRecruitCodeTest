<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


  /**
   * Team is a new model tied to the database with the following columns
   * Team has a one to many relationship with the User model
  */
class Team extends Model
{

  #assign the relationship to users
  public function users()
  {
    return $this->hasMany(User::class);
  }

  public static function getTeamWithLeastAmountOfPlayers($tournament)
  {
    return self::where('tournament', $tournament)->orderBy('number_of_players')->first();
  }

  public static function getLowestRatedTeam($tournament)
  {
    return self::where('tournament', $tournament)->where('number_of_players', '<', 22)->orderBy('total_player_ranking')->first();
  }

  #this would normally be handled in a model named Tournament that has teams
  public static function getTeamsForATournament($tournament)
  {
    return self::where('tournament',$tournament)->inRandomOrder()->get();
  }

  public function addPlayer($player)
  {
    $this->total_player_ranking += $player->ranking;
    $this->number_of_players++;
    $player->addTeam($this->id);
    $this->save();

  }

  public function getNumberOfPlayers()
  {
    return $this->number_of_players;
  }



   
}
