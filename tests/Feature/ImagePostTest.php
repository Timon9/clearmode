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
}
