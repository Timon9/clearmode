<?php

namespace Tests\Browser;

use App\Models\User;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Laravel\Dusk\Browser;
use Storage;
use Tests\DuskTestCase;

class CreateImagePostTest extends DuskTestCase
{
    use WithFaker;

    /**
     * Test that the user can create an image post.
     */
    public function testUserCanCreateAnImagePost(): void
    {
        $title = $this->faker->sentence();
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $title) {
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
            Storage::delete('public/images/'.$file->getClientOriginalName());

        });
    }
}
