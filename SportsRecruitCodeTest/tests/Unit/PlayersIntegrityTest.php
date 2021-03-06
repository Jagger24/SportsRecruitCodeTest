<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;

class PlayersIntegrityTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGoaliePlayersExist () 
    {
/*
		Check there are players that have can_play_goalie set as 1   
*/
		$result = User::where('user_type', 'player')->where('can_play_goalie', 1)->count();
		$this->assertTrue($result > 1);
	
    }
    public function testAtLeastOneGoaliePlayerPerTeam () 
    {
/*
	    calculate how many teams can be made so that there is an even number of teams and they each have between 18-22 players.
	    Then check that there are at least as many players who can play goalie as there are teams
*/
        $goalie_count = User::where('user_type', 'player')->where('can_play_goalie', 1)->count();
        $player_count = User::where('user_type', 'player')->count();
        $number_of_teams = floor($player_count/18);
        $number_of_teams = ($number_of_teams % 2 == 0) ? $number_of_teams : $number_of_teams - 1;

        $this->assertTrue($goalie_count >= $number_of_teams);

    }
}
