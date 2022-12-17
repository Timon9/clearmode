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
}
