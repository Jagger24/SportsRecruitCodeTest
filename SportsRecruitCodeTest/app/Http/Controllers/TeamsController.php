<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Team;
use Faker\Factory as Faker;

class TeamsController extends Controller
{

    #Each time the page load create a new tournament of teams
    public function __construct()
    {
        $this->players = User::getPlayersGroupedByRank();
        $number_of_teams = floor(sizeof($this->players)/18);
        $number_of_teams = ($number_of_teams % 2 == 0) ? $number_of_teams : $number_of_teams - 1;
        $faker = Faker::create();
        $this->tournament = $faker->words($nb = 6, $variableNbWords = true);
        $this->createNewTeams($number_of_teams, $faker);
    }

    #Assign players to teams fairly where team sizes are between 18 and 22
    public function generateFullTeams()
    {
        $number_of_players_left_to_assign = sizeof($this->players);
        foreach($this->players as $player){
            $lowest_player_count_team = Team::getTeamWithLeastAmountOfPlayers($this->tournament);

            #so long as we will have atleast 18-22 players
            if($lowest_player_count_team->getNumberOfPlayers() + $number_of_players_left_to_assign > 18 ){
                # assign to team with lowest team ranking
                $lowest_rated_team = Team::getLowestRatedTeam($this->tournament);
                $lowest_rated_team->addPlayer($player);
                
            }else{
                # assign to team that desperatly needs players despite high team rank
                $player->addTeam($lowest_player_count_team->id);
            }
            $number_of_players_left_to_assign -= 1;
        }

        $teams = Team::getTeamsForATournament($this->tournament);

        return view('teams', ['teams' => Team::getTeamsForATournament($this->tournament)]); 
    }

    #create new teams based off the number of players in the tournament
    private function createNewTeams($number_of_teams, $faker)
    {
        $faker = Faker::create();
        $tournament = $faker->words($nb = 6, $variableNbWords = true);
        $new_teams = [];
        for ($new_teams_made = 0; $new_teams_made < $number_of_teams; $new_teams_made++) {
            $team = new Team();
            $team->name = 'The ' . $faker->jobTitle . 's';
            $team->tournament = $this->tournament;
            $team->save();
            array_push($new_teams, $team);
        }
        return $new_teams;
    }
    
}
