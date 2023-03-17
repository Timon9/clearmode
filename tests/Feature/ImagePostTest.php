<?php

namespace Tests\Feature;

use App\Models\ImagePost;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImagePostTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * Test if we can view a ImagePost
     *
     * @return void
     */
    public function test_image_post_endpoint()
    {
        $imagePost = ImagePost::factory()->create();
        $user = User::factory()->create();
        $response = $this->get('@'.$user->slug.'/i/'. $imagePost->slug);
        $response->assertStatus(200);
    }
    /**
     * Test if we can view a ImagePost title
     *
     * @return void
     */
    public function test_image_post_have_titles()
    {
        $imagePost = ImagePost::factory()->create();
        $user = User::factory()->create();
        $response = $this->get('@'.$user->slug.'/i/'. $imagePost->slug);
        $response->assertOk()->assertSeeText($imagePost->title);
    }

        /**
     * Test if we can view a ImagePost
     *
     * @return void
     */
    public function test_image_post_contains_image_url()
    {
        $imagePost = ImagePost::factory()->create();
        $user = User::factory()->create();
        $response = $this->get('@'.$user->slug.'/i/'. $imagePost->slug);
        $response->assertOk()->assertSee($imagePost->url);
    }

}
