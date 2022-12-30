<?php

namespace Tests\Feature;

use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\TeamRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /**
     * Test if the team index is displayed (/teams)
     *
     * @return void
     */

    public function test_team_index_is_displayed()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/teams');

        $response->assertOk();
    }

    /**
     * Test if the team view is displayed (/teams/slug)
     *
     * @return void
     */

    public function testCanViewTeamUsingSlug()
    {
        $user = User::factory()->create();

        // Create a user and a team
        $user = User::factory()->create();
        $team = Team::factory(["name" => "The same name"]) // The slug should auto. use increments "the-same-name-N"
            ->hasAttached($user)
            ->create();
        $team2 = Team::factory(["name" => "The same name"]) // The slug should auto. use increments "the-same-name-N"
            ->hasAttached($user)
            ->create();

        $this->assertNotEmpty($team->slug); //the-same-name
        $this->assertNotEmpty($team2->slug); //the-same-name-2
        $this->assertNotSame($team->slug, $team2->slug);

        $response = $this
            ->actingAs($user)
            ->get('/teams/' . $team->slug);

        $response->assertOk();
    }

    /**
     * Test the index pagination.
     *
     * @return void
     */
    public function testIndexResultsUsePagination()
    {
        // Create a user and 30 teams
        $user = User::factory()->create();
        Team::factory()
            ->count(30)
            ->hasAttached($user)
            ->create();

        // Send a GET request to the index page
        $response = $this->actingAs($user)->get('/teams');

        // Assert that the response is successful
        $response->assertSuccessful();

        // Assert that the first page of teams is displayed
        $response->assertViewIs('teams.index');
        $response->assertViewHas('teams');
        $teams = $response->viewData('teams');
        $this->assertEquals(25, $teams->count());
        $this->assertEquals(1, $teams->currentPage());
        $this->assertEquals(30, $teams->total());
        $this->assertEquals(2, $teams->lastPage());

        // Send a GET request to the second page
        $response = $this->actingAs($user)->get('/teams?page=2');

        // Assert that the second page of teams is displayed
        $response->assertSuccessful();
        $response->assertViewIs('teams.index');
        $response->assertViewHas('teams');
        $teams = $response->viewData('teams');
        $this->assertEquals(5, $teams->count());
        $this->assertEquals(2, $teams->currentPage());
        $this->assertEquals(30, $teams->total());
        $this->assertEquals(2, $teams->lastPage());
    }

    /**
     * Test the index search.
     *
     * @return void
     */
    public function testYouCanSearchTeams()
    {
        // Create a user and some teams
        $user = User::factory()->create();
        $teams = Team::factory()
            ->count(5)
            ->hasAttached($user)
            ->create();


        // Set the search query to the name of one of the teams
        $search = $teams[0]->name;

        // Send a GET request to the search route with the search query
        $response = $this->actingAs($user)->get("/teams?search={$search}");

        // Assert that the response contains the correct data
        $response->assertOk()
            ->assertSee($search);

        // Send a GET request to the search route with a random query
        $response = $this->actingAs($user)->get("/teams?search=" . $this->faker()->uuid());

        // Assert that the response is not containing the team
        $response->assertOk()
            ->assertDontSee($search);
    }

    /**
     * Test if the teams/create is displayed
     *
     * @return void
     */

    public function test_team_create_is_displayed()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/teams/create');

        $response->assertOk();
    }


    /**
     * Test if a User can create a public Team.
     *
     * @return void
     */
    public function testUserCanCreateAPublicTeam()
    {
        // Create a user
        $user = User::factory()->create();
        $teamName = $this->faker()->uuid(); // Generate a random team name

        // Send a POST request to the team creation endpoint as the user
        $response = $this->actingAs($user)->post('/teams', [
            'name' => $teamName,
            'visibility' => 'public',
        ]);

        // Assert that there are no validation errors and that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Find the newly created Team
        $team = Team::where(['name' => $teamName])->firstOrFail();
        // Assert that the user is member of the team with the specified name
        $this->assertTrue($user->teams->contains($team));
        $this->assertTrue($team->public);
    }
    /**
     * Test if a User can create a PRIVATE Team.
     *
     * @return void
     */
    public function testUserCanCreateAPrivateTeam()
    {
        // Create a user
        $user = User::factory()->create();
        $teamName = $this->faker()->uuid(); // Generate a random team name

        // Send a POST request to the team creation endpoint as the user
        $response = $this->actingAs($user)->post('/teams', [
            'name' => $teamName,
            'visibility' => 'private',
        ]);

        // Assert that there are no validation errors and that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Find the newly created Team
        $team = Team::where(['name' => $teamName])->firstOrFail();
        // Assert that the user is member of the team with the specified name
        $this->assertTrue($user->teams->contains($team));
        $this->assertFalse($team->public);
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
        $response = $this->actingAs($user)->post('/teams', [
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
        $response = $this->actingAs($user)->delete("/teams/{$team->slug}");

        // Assert that the team was deleted and the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);

        // Send a DELETE request to the other team deletion endpoint as the user. It should return an error
        // because user has no permission
        $response = $this->actingAs($user)->delete("/teams/{$otherTeam->slug}");
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
        $response = $this->actingAs($user)->patch("/teams/{$team->slug}", ["name" => $newName]);

        // Assert that the team was edited and the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'name' => $newName]);

        // Send a PATCH request to the team edit endpoint as the other user. It should return an error
        // because user has no permission
        $response = $this->actingAs($user)->patch("/teams/{$otherTeam->slug}", ["name" => $newName]);
        $response->assertForbidden();
        $this->assertDatabaseHas('teams', ['id' => $otherTeam->id]);
        $this->assertDatabaseMissing('teams', ['id' => $otherTeam->id, 'name' => $newName]);
    }

    /**
     * Test if a user can join a team public.
     *
     * @return void
     */
    public function testUserCanJoinAndUnjoinAPublicTeam()
    {
        // // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create(["public" => true]);
        $user->teams()->attach([$team->id]);

        $secondUser = User::factory()->create();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/teams/{$team->slug}/join");

        // Assert that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Assert the second user has joined the team
        $this->assertTrue($secondUser->teams->contains($team));


        $teamRole = TeamRole::where(['team_id' => $team->id, 'user_id' => $secondUser->id])->firstOrFail();

        // Assert that the user is member of the team with an MEMBER role
        $this->assertEquals(TeamRoleEnum::MEMBER->value, $teamRole->role);


        // Send a POST request to the team unjoin endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/teams/{$team->slug}/unjoin");

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
     * Test if a user can join a private team with an invite only.
     *
     * @return void
     */
    public function testUserCanJoinAndUnjoinAPrivateTeamWithAnInvite()
    {
        // // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create();
        $user->teams()->attach([$team->id]);

        $secondUser = User::factory()->create();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/teams/{$team->slug}/join");

        // Assert that the user was given a 403, you need an invite to join
        $response->assertStatus(403);

        // Assert the second user has not joined the team. You can't witout an invite.
        $this->assertFalse($secondUser->teams->contains($team));

        // Create an invite
        $teamInvite = new TeamInvite();
        $teamInvite->user_id = $secondUser->id;
        $teamInvite->team_id = $team->id;
        $teamInvite->save();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/teams/{$team->slug}/join");

        // Assert that the second user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();



        $teamRole = TeamRole::where(['team_id' => $team->id, 'user_id' => $secondUser->id])->firstOrFail();

        // Assert that the user is member of the team with an MEMBER role
        $this->assertEquals(TeamRoleEnum::MEMBER->value, $teamRole->role);


        // Send a POST request to the team unjoin endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/teams/{$team->slug}/unjoin");

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
        $team = Team::factory()->create(["public" => true]);
        $user->teams()->attach([$team->id]);

        $otherUser = User::factory()->create();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($otherUser)->post("/teams/{$team->slug}/join");

        // Assert that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Assert the second user has joined the team
        $this->assertTrue($otherUser->teams->contains($team));


        // Send a PATCH request to the team edit endpoint as the otherUser. It should return an error because
        // the user is only a MEMBER
        $response = $this->actingAs($otherUser)->patch("/teams/{$team->slug}", ["name" => $newName]);
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
        $team = Team::factory()->create(["public" => true]);
        $user->teams()->attach([$team->id]);

        $otherUser = User::factory()->create();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($otherUser)->post("/teams/{$team->slug}/join");

        // Assert that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Assert the second user has joined the team
        $this->assertTrue($otherUser->teams->contains($team));


        // Send a DELETE request to the team edit endpoint as the otherUser. It should return an error because
        // the user is only a MEMBER
        $response = $this->actingAs($otherUser)->delete("/teams/{$team->slug}");
        $response->assertForbidden();
        $this->assertDatabaseHas('teams', ['id' => $team->id]);
    }

    /**
     * Test if an admin is not able to join.
     *
     * @return void
     */
    public function testTeamAdminCantUnjoin()
    {
        // Create a user
        $user = User::factory()->create();
        $teamName = $this->faker()->uuid(); // Generate a random team name

        // Send a POST request to the team creation endpoint as the user
        $response = $this->actingAs($user)->post('/teams', [
            'name' => $teamName
        ]);

        // Assert that there are no validation errors and that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Find the newly created Team
        $team = Team::where(['name' => $teamName])->firstOrFail();
        // Assert that the user is member of the team with the specified name
        $this->assertTrue($user->teams->contains($team));

        // Make user an admin
        $teamRole = new TeamRole();
        $teamRole->team_id = $team->id;
        $teamRole->user_id = $user->id;
        $teamRole->role = TeamRoleEnum::ADMIN;
        $teamRole->save();

        // Send a POST request to the team unjoin endpoint as the second user
        $response = $this->actingAs($user)->post("/teams/{$team->slug}/unjoin");
        $response->assertStatus(403);
        $user->refresh();

        // Assert that the user is member of the team with the specified name
        $this->assertTrue($user->teams->contains($team));
    }

    /**
     * Test if a user can join a team and can be made an admin.
     *
     * @return void
     */
    public function testUserBeGivenAnAdminRoleByAnAdminButNotByAUser()
    {
        // // Create a user and a team owned by the user
        $user = User::factory()->create();
        $team = Team::factory()->create(["public" => true]);
        $user->teams()->attach([$team->id]);

        // Make user admin
        $teamRole = new TeamRole();
        $teamRole->team_id = $team->id;
        $teamRole->user_id = $user->id;
        $teamRole->role = TeamRoleEnum::ADMIN;
        $teamRole->save();

        $secondUser = User::factory()->create();

        // Send a POST request to the team join endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/teams/{$team->slug}/join");

        // Assert that the user was redirected
        $response->assertSessionHasNoErrors()->assertRedirect();

        // Assert the second user has joined the team
        $this->assertTrue($secondUser->teams->contains($team));

        $teamRole = TeamRole::where(['team_id' => $team->id, 'user_id' => $secondUser->id])->firstOrFail();

        // Assert that the user is member of the team with an MEMBER role
        $this->assertEquals(TeamRoleEnum::MEMBER->value, $teamRole->role);

        // Send a POST request to the team add-role endpoint as the second user
        $response = $this->actingAs($secondUser)->post("/teams/{$team->slug}/role", ['user_id' => $secondUser->id, 'role' => TeamRoleEnum::ADMIN->value]);

        // Assert this has failed. A MEMBER can't promote itself to ADMIN
        $this->assertEquals(0, TeamRole::where(['team_id' => $team->id, 'user_id' => $secondUser->id, 'role' => TeamRoleEnum::ADMIN->value])->count());

        // Send a POST request to the team add-role endpoint as the first user
        $response = $this->actingAs($user)->post("/teams/{$team->slug}/role", ['user_id' => $secondUser->id, 'role' => TeamRoleEnum::ADMIN->value]);

        // Assert this has succeeded. secondUser is now ADMIN
        $this->assertEquals(1, TeamRole::where(['team_id' => $team->id, 'user_id' => $secondUser->id, 'role' => TeamRoleEnum::ADMIN->value])->count());
    }
}
