<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{

    use RefreshDatabase; // refresh the database after each test

    /** @test */
    public function many_users_can_have_many_teams()
    {
        // create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // create three teams
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();
        $team3 = Team::factory()->create();

        // assign teams to users
        $user1->teams()->attach([$team1->id, $team2->id]);
        $user2->teams()->attach([$team2->id, $team3->id]);

        // assert that the users have the correct teams
        $this->assertEquals([$team1->id, $team2->id], $user1->teams->pluck('id')->toArray());
        $this->assertEquals([$team2->id, $team3->id], $user2->teams->pluck('id')->toArray());
    }

    /** @test */
    public function many_teams_can_have_many_users()
    {
        // create three users
        $user = User::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        // create two teams
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();

        // assign users to teams
        $team1->users()->attach([$user1->id, $user2->id]);
        $team2->users()->attach([$user2->id, $user3->id]);

        // assert that the teams have the correct users
        $this->assertEquals([$user1->id, $user2->id], $team1->users->pluck('id')->toArray());
        $this->assertEquals([$user2->id, $user3->id], $team2->users->pluck('id')->toArray());
    }
}
