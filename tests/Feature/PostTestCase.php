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
        $response = $this->get('@'.$user->slug.'/'. $post->id."/".$post->slug);
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
        $response = $this->get('@'.$user->slug.'/'. $post->id."/".$post->slug);
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
        $post = $this->model::factory()->create();

        // Act
        $this->get('@'.$user->slug.'/'. $post->id."/".$post->slug)->assertOk(); // Test if the creation succeeded
        $this->delete('@'.$user->slug.'/'. $post->id."/".$post->slug)->assertOk(); // Send the delete request

        // Assert
        $this->get('@'.$user->slug.'/'. $post->id."/".$post->slug)->assertNotFound(); // Should now be removed and return a 404
    }
}

