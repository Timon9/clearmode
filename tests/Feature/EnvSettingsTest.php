<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnvSettingsTest extends TestCase
{

    /** @test */
    public function it_checks_if_google_socialite_variables_are_set(): void
    {
        $this->assertNotEmpty(env('GOOGLE_CLIENT_ID'));
        $this->assertNotEmpty(env('GOOGLE_CLIENT_SECRET'));
        $this->assertNotEmpty(env('GOOGLE_CLIENT_REDIRECT'));
    }


    /** @test */
    public function it_checks_if_mysql_variables_are_set(): void
    {
        $this->assertNotEmpty(env('DB_CONNECTION'));
        $this->assertNotEmpty(env('DB_HOST'));
        $this->assertNotEmpty(env('DB_PORT'));
        $this->assertNotEmpty(env('DB_DATABASE'));
        $this->assertNotEmpty(env('DB_USERNAME'));
        $this->assertNotEmpty(env('DB_PASSWORD'));
    }

    /** @test */
    public function it_checks_if_mail_variables_are_set(): void
    {
        $this->assertNotEmpty(env('MAIL_MAILER'));
        $this->assertNotEmpty(env('MAIL_HOST'));
        $this->assertNotEmpty(env('MAIL_PORT'));
        $this->assertNotNull(env('MAIL_FROM_ADDRESS'));
        $this->assertNotNull(env('MAIL_FROM_NAME'));
    }

    /** @test */
    public function it_checks_if_vite_variables_are_set(): void
    {
        $this->assertNotNull(env('VITE_PUSHER_APP_KEY'));
        $this->assertNotNull(env('VITE_PUSHER_HOST'));
        $this->assertNotNull(env('VITE_PUSHER_PORT'));
        $this->assertNotNull(env('VITE_PUSHER_SCHEME'));
        $this->assertNotNull(env('VITE_PUSHER_APP_CLUSTER'));
    }


    /** @test */
    public function it_checks_if_generic_laravel_variables_are_set(): void
    {
        $this->assertNotNull(env('APP_NAME'));
        $this->assertNotNull(env('APP_ENV'));
        $this->assertNotNull(env('APP_KEY'));
        $this->assertNotNull(env('APP_DEBUG'));
        $this->assertNotNull(env('APP_URL'));
        $this->assertNotNull(env('LOG_CHANNEL'));
        $this->assertNotNull(env('LOG_LEVEL'));
        $this->assertNotNull(env('BROADCAST_DRIVER'));
        $this->assertNotNull(env('CACHE_DRIVER'));
        $this->assertNotNull(env('FILESYSTEM_DISK'));
        $this->assertNotNull(env('QUEUE_CONNECTION'));
        $this->assertNotNull(env('SESSION_DRIVER'));
        $this->assertNotNull(env('SESSION_LIFETIME'));
        $this->assertNotNull(env('MEMCACHED_HOST'));
        $this->assertNotNull(env('REDIS_HOST'));
        $this->assertNotNull(env('REDIS_PORT'));
    }
}
