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
    use RefreshDatabase, WithFaker;

    /**
     * Test if a User can create a Team.
     *
     * @return void
     */
    public function testUserCanCreateATeam()
    {
        // Create a user
        $user = User::factory()->create();
        $teamName = $this->faker()->uuid(); // Generate a random team name

        // Send a POST request to the team creation endpoint as the user
        $response = $this->actingAs($user)->post('/team', [
            'name' => $teamName
        ]);

        // Assert that there are no validation errors and that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Assert that the user owns the team with the specified name
        $team = Team::where(['user_id' => $user->id, 'name' => $teamName])->first();
        $this->assertNotEmpty($team);
    }

    /**
     * Test if a user can delete a team.
     *
     * @return void
     */
    public function testUserCanDeleteATeam()
    {
        // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);

        $OtherUser = User::factory()->create();
        $otherTeam = Team::factory()->create(['user_id' => $OtherUser->id]);

        // Send a DELETE request to the team deletion endpoint as the user
        $response = $this->actingAs($user)->delete("/team/{$team->id}");

        // Assert that the team was deleted and the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);

        // Send a DELETE request to the other team deletion endpoint as the user. It should return an error
        // because user has no permission
        $response = $this->actingAs($user)->delete("/team/{$otherTeam->id}");
        $response->assertForbidden();
        $this->assertDatabaseHas('teams', ['id' => $otherTeam->id]);
    }

        /**
     * Test if a user can delete a team.
     *
     * @return void
     */
    public function testUserCanEditATeam()
    {
        $newName = $this->faker()->uuid();

        // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);

        $OtherUser = User::factory()->create();
        $otherTeam = Team::factory()->create(['user_id' => $OtherUser->id]);

        // Send a PATCH request to the team edit endpoint as the user
        $response = $this->actingAs($user)->patch("/team/{$team->id}",["name"=>$newName]);

        // Assert that the team was edited and the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseHas('teams', ['id' => $team->id,'name'=>$newName]);

        // Send a PATCH request to the team edit endpoint as the other user. It should return an error
        // because user has no permission
        $response = $this->actingAs($user)->patch("/team/{$otherTeam->id}",["name"=>$newName]);
        $response->assertForbidden();
        $this->assertDatabaseHas('teams', ['id' => $otherTeam->id]);
        $this->assertDatabaseMissing('teams', ['id' => $otherTeam->id,'name'=>$newName]);

    }
}
