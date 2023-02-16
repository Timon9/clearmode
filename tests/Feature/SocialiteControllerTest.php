<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class SocialiteControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_redirects_to_google_oauth2_process()
    {
        $response = $this->get(route('socialite-google-redirect'));
        $response->assertStatus(302);
    }

    /** @test */
    public function it_logs_in_using_google_and_creates_or_updates_user()
    {

        // Arrange
        $mockSocalUser = $this->Mock(SocialUser::class);
        $mockSocalUser->id = 1234567890;
        $mockSocalUser->name = "Mock User";
        $mockSocalUser->email = "mock@user.laravel";

        $mockSocialiteProver = $this->Mock(Provider::class);
        $mockSocialiteProver->shouldReceive('user')->once()->andReturn($mockSocalUser);

        Socialite::shouldReceive('driver')->andReturn($mockSocialiteProver);

        // Act
        $this->get(route('socialite-google-callback'));

        // Assert
        $this->assertDatabaseHas('users', [
            'google_id' => $mockSocalUser->id,
            'name' => $mockSocalUser->name,
            'email' => $mockSocalUser->email,
        ]);
    }
}
