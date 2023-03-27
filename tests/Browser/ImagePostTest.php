<?php

namespace Tests\Browser;

use App\Models\ImagePost;
use App\Models\User;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Image;
use Laravel\Dusk\Browser;
use Storage;
use Tests\DuskTestCase;

class ImagePostTest extends DuskTestCase
{
    use WithFaker, DatabaseMigrations;

    /**
     * Test that the user can create an image post.
     */
    public function testUserCanCreateAnImagePost(): void
    {

        $this->browse(function (Browser $browser) {

            $title = $this->faker->sentence();
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visit('posts/image/create');

            $browser->type('title', $title);

            // Create a mock image file and attach it
            $file = HttpUploadedFile::fake()->image('test-image.jpg');
            Storage::putFileAs('public/images', $file, $file->getClientOriginalName());
            $browser->attach('image_file', storage_path('app/public/images/' . $file->getClientOriginalName()));


            $browser->press('submit');

            // Assert that the post was created successfully
            $browser->assertPathBeginsWith('/@' . $user->slug)
                ->assertSee($title);

            // Assert that the uploaded file is displayed on the page
            $browser->assertVisible('#imagePost');

            // Cleanup
            Storage::delete('public/images/' . $file->getClientOriginalName());
        });
    }

      /**
     * Test that the user can delete an image post.
     */
    public function testUserCanDeleteAnImagePost(): void
    {

        $this->browse(function (Browser $browser) {

            // Arrange
            $user = User::factory()->create();
            $imagePost = ImagePost::factory()->create([
                'user_id'=>$user->id
            ]);

            $browser->loginAs($user)
                ->visit('/@'.$user->slug.'/'.$imagePost->id.'/'.$imagePost->slug)->assertSee($imagePost->title);

            // Act
            $browser->press('delete_post');
        });
    }
}
