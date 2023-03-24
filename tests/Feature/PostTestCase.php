<?php

namespace Tests\Feature;

use App\Models\ImagePost;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class PostTestCase extends TestCase
{

    use WithFaker, DatabaseMigrations;

    public function __construct(protected Model $model)
    {
        parent::__construct();
    }

    /**
     * Test if we can view the post after creation by the user
     *
     * @return void
     */
    public function test_post_can_be_viewed_after_creation()
    {
        $post = $this->model::factory()->create();
        $user = User::factory()->create();
        $response = $this->get('@' . $user->slug . '/' . $post->id . "/" . $post->slug);
        $response->assertStatus(200);
    }
    /**
     * Test if we can view a Post title
     *
     * @return void
     */
    public function test_post_have_titles()
    {
        $post = $this->model::factory()->create();
        $user = User::factory()->create();
        $response = $this->get('@' . $user->slug . '/' . $post->id . "/" . $post->slug);
        $response->assertOk()->assertSeeText($post->title);
    }

    /**
     *
     *
     * @return void
     */
    public function test_post_can_be_deleted_by_the_user()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);
        $post = $this->model::factory(['user_id' => $user->id])->create();

        // Act
        $this->get('@' . $user->slug . '/' . $post->id . "/" . $post->slug)->assertOk(); // Test if the creation succeeded
        $this->delete('@' . $user->slug . '/' . $post->id . "/" . $post->slug)->assertOk(); // Send the delete request

        // Assert
        $this->get('@' . $user->slug . '/' . $post->id . "/" . $post->slug)->assertNotFound(); // Should now be removed and return a 404
    }

    public function test_post_can_not_be_deleted_by_a_user_thats_not_the_creator()
    {
        // Arrange
        $user = User::factory()->create();
        $post = $this->model::factory(['user_id' => $user->id])->create();

        // Login as another user
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);

        // Act
        $this->get('@' . $user->slug . '/' . $post->id . "/" . $post->slug)->assertOk(); // Test if the creation succeeded
        $this->delete('@' . $user->slug . '/' . $post->id . "/" . $post->slug)->assertForbidden(); // Send the delete request should result in 403

        // Assert
        $this->get('@' . $user->slug . '/' . $post->id . "/" . $post->slug)->assertOk(); // Should not be removed
    }

    public function test_post_should_be_deleted_by_cascade_if_creator_is_deleted()
    {
    }

    public function test_request_data_should_be_validated_before_creation()
    {
    }
}
