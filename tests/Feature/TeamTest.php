<?php

namespace Tests\Feature;

use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\TeamRole;
use App\Models\User;
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

        // Find the newly created Team
        $team = Team::where(['name' => $teamName])->firstOrFail();
        // Assert that the user is member of the team with the specified name
        $this->assertTrue($user->teams->contains($team));
    }

    /**
     * Test if a User who created a Team becomes an admin.
     *
     * @return void
     */
    public function testUserWillAutomaticlyBecomeAdminAfterCreatingATeam()
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

        // Find the newly created Team
        $team = Team::where(['name' => $teamName])->firstOrFail();

        $teamRole = TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id])->firstOrFail();

        // Assert that the user is member of the team with an ADMIN role
        $this->assertEquals(TeamRoleEnum::ADMIN->value, $teamRole->role);
    }

    /**
     * Test if a user can delete a team if the user has admin rights.
     *
     * @return void
     */
    public function testUserCanDeleteATeamAsAdmin()
    {
        // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create();
        $user->teams()->attach([$team->id]);

        $otherUser = User::factory()->create();
        $otherTeam = Team::factory()->create();
        $otherUser->teams()->attach([$otherTeam->id]);

        $teamRole = new TeamRole();
        $teamRole->team_id = $team->id;
        $teamRole->user_id = $user->id;
        $teamRole->role = TeamRoleEnum::ADMIN;
        $teamRole->save();

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
     * Test if a user can edit a team if the user has admin rights.
     *
     * @return void
     */
    public function testUserCanEditATeamAsAdmin()
    {
        $newName = $this->faker()->uuid();

        // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create();
        $user->teams()->attach([$team->id]);

        $teamRole = new TeamRole();
        $teamRole->team_id = $team->id;
        $teamRole->user_id = $user->id;
        $teamRole->role = TeamRoleEnum::ADMIN;
        $teamRole->save();

        $otherUser = User::factory()->create();
        $otherTeam = Team::factory()->create();
        $otherUser->teams()->attach([$otherTeam->id]);


        // Send a PATCH request to the team edit endpoint as the user
        $response = $this->actingAs($user)->patch("/team/{$team->id}", ["name" => $newName]);

        // Assert that the team was edited and the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'name' => $newName]);

        // Send a PATCH request to the team edit endpoint as the other user. It should return an error
        // because user has no permission
        $response = $this->actingAs($user)->patch("/team/{$otherTeam->id}", ["name" => $newName]);
        $response->assertForbidden();
        $this->assertDatabaseHas('teams', ['id' => $otherTeam->id]);
        $this->assertDatabaseMissing('teams', ['id' => $otherTeam->id, 'name' => $newName]);
    }

    /**
     * Test if a user can join a team.
     *
     * @return void
     */
    public function testUserCanJoinAndUnjoinATeam()
    {
        // // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create();
        $user->teams()->attach([$team->id]);

        $secondUser = User::factory()->create();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/team/{$team->id}/join");

        // Assert that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Assert the second user has joined the team
        $this->assertTrue($secondUser->teams->contains($team));


        $teamRole = TeamRole::where(['team_id' => $team->id, 'user_id' => $secondUser->id])->firstOrFail();

        // Assert that the user is member of the team with an MEMBER role
        $this->assertEquals(TeamRoleEnum::MEMBER->value, $teamRole->role);


        // Send a POST request to the team unjoin endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/team/{$team->id}/unjoin");

        // Assert that the second user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        $secondUser->refresh();

        // Assert the second user has left the team
        $this->assertFalse($secondUser->teams->contains($team));

        // Assert that TeamRole has been deleted
        $teamRole = TeamRole::where(['team_id' => $team->id, 'user_id' => $secondUser->id])->first();
        $this->assertEmpty($teamRole);
    }

    /**
     * Test if a user can not edit a team as a member.
     *
     * @return void
     */
    public function testUserCanNotEditATeamAsAMember()
    {
        $newName = $this->faker()->uuid();

        // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create();
        $user->teams()->attach([$team->id]);

        $otherUser = User::factory()->create();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($otherUser)->post("/team/{$team->id}/join");

        // Assert that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Assert the second user has joined the team
        $this->assertTrue($otherUser->teams->contains($team));


        // Send a PATCH request to the team edit endpoint as the otherUser. It should return an error because
        // the user is only a MEMBER
        $response = $this->actingAs($otherUser)->patch("/team/{$team->id}", ["name" => $newName]);
        $response->assertForbidden();
        $this->assertDatabaseHas('teams', ['id' => $team->id]);
        $this->assertDatabaseMissing('teams', ['id' => $team->id, 'name' => $newName]);
    }

    /**
     * Test if a user can not delete a team as a member.
     *
     * @return void
     */
    public function testUserCanNotDeleteATeamAsAMember()
    {

        // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create();
        $user->teams()->attach([$team->id]);

        $otherUser = User::factory()->create();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($otherUser)->post("/team/{$team->id}/join");

        // Assert that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Assert the second user has joined the team
        $this->assertTrue($otherUser->teams->contains($team));


        // Send a DELETE request to the team edit endpoint as the otherUser. It should return an error because
        // the user is only a MEMBER
        $response = $this->actingAs($otherUser)->delete("/team/{$team->id}");
        $response->assertForbidden();
        $this->assertDatabaseHas('teams', ['id' => $team->id]);
    }

    /**
     * Test if a User can create a Team.
     *
     * @return void
     */
    public function testTeamAdminCantUnjoin()
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

        // Find the newly created Team
        $team = Team::where(['name' => $teamName])->firstOrFail();
        // Assert that the user is member of the team with the specified name
        $this->assertTrue($user->teams->contains($team));

        // Send a POST request to the team unjoin endpoint as the second user
        $response = $this->actingAs($user)->post("/team/{$team->id}/unjoin");
        $user->refresh();

        // Assert that the user is member of the team with the specified name
        $this->assertTrue($user->teams->contains($team));
    }

    /**
     * Test if a user can join a team and can be made an admin.
     *
     * @return void
     */
    public function testUserCanJoinAndUnjoinATeamAndBeGivenAnAdminRole()
    {
        // // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create();
        $user->teams()->attach([$team->id]);

        $secondUser = User::factory()->create();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/team/{$team->id}/join");

        // Assert that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Assert the second user has joined the team
        $this->assertTrue($secondUser->teams->contains($team));

        $teamRole = TeamRole::where(['team_id' => $team->id, 'user_id' => $secondUser->id])->firstOrFail();

        // Assert that the user is member of the team with an MEMBER role
        $this->assertEquals(TeamRoleEnum::MEMBER->value, $teamRole->role);

        // Send a POST request to the team add-role endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/team/{$team->id}/role", ['user_id' => $secondUser->id, 'role' => TeamRoleEnum::ADMIN]);

        // Assert this has failed. A MEMBER can't promote itself to ADMIN
        $this->assertEquals(0, TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id, 'role' => TeamRoleEnum::ADMIN->value])->count());

        // Send a POST request to the team add-role endpoint as the first user
        $response = $this->actingAs($user)->post("/team/{$team->id}/role", ['user_id' => $secondUser->id, 'role' => TeamRoleEnum::ADMIN]);

        // Assert this has succeeded. secondUser is now ADMIN
        $this->assertEquals(1, TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id, 'role' => TeamRoleEnum::ADMIN->value])->count());
    }
}
