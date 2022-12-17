<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase,WithFaker;

    /**
     * Test if a User can create a Team.
     *
     * @return void
     */
    public function testUserCanCreateATeam()
    {
        $user = User::Factory()->create();
        $teamName = $this->faker()->uuid();

        $response = $this
        ->actingAs($user)
        ->post('/teams', [
            'name' => $teamName
        ]);

        $response
        ->assertSessionHasNoErrors()
        ->assertRedirect();

        // Assert $user owns team
        $team = Team::where(['user_id'=>$user->id,'name'=>$teamName])->first();
        $this->assertNotEmpty($team);



    }
}
