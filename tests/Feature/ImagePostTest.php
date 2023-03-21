<?php

namespace Tests\Feature;

use App\Models\ImagePost;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImagePostTest extends TestCase
{
    use DatabaseMigrations, WithFaker;
    /**
     * Test if we can view a ImagePost
     *
     * @return void
     */
    public function test_image_post_endpoint()
    {
        $imagePost = ImagePost::factory()->create();
        $user = User::factory()->create();
        $response = $this->get('@'.$user->slug.'/'. $imagePost->id."/".$imagePost->slug);
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
        $response = $this->get('@'.$user->slug.'/'. $imagePost->id."/".$imagePost->slug);
        $response->assertOk()->assertSeeText($imagePost->title);
    }

    /**
     * Test if we can view a ImagePost url
     *
     * @return void
     */
    public function test_image_post_contains_image_url()
    {
        $imagePost = ImagePost::factory()->create();
        $user = User::factory()->create();
        $response = $this->get('@'.$user->slug.'/'. $imagePost->id."/".$imagePost->slug);
        $response->assertOk()->assertSee($imagePost->url);
    }

    public function test_user_can_create_image_post(){
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake();

        $fakeTitle = $this->faker()->uuid(); // Use uuidv4 to be sure the title is unique
        $this->post("/posts/image/store",[
            "title"=>$fakeTitle,
            "image_file"=>UploadedFile::fake()->image($fakeTitle.".jpg",800,800)
        ])->assertRedirect();

        $this->assertDatabaseHas("image_posts",[
            'title'=>$fakeTitle
        ]);

        // Test if the uploaded image file is accessible via the url
        $imagePost = ImagePost::where(["title"=>$fakeTitle])->firstOrFail();
        $this->get($imagePost->url)->assertOk();

    }

    /**
     * Test if we can access the image post form
     *
     * @return void
     */
    public function test_image_post_form_is_accessible(){

        // Test if get a redirect if not logged in
        $this->get("/posts/image/create")->assertRedirect();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get("/posts/image/create")->assertOk();
    }

    public function test_cant_create_image_post_if_not_logged_in(){
        $fakeTitle = $this->faker()->uuid(); // Use uuidv4 to be sure the title is unique

        Storage::fake(); // It should't upload files, but just to be sure.

        $this->post("/posts/image/store",[
            "title"=>$fakeTitle,
            "image_file"=>UploadedFile::fake()->image("file.jpg",80,80)
        ])->assertRedirect();

        $this->assertDatabaseMissing("image_posts",[
            'title'=>$fakeTitle
        ]);


    }

}
