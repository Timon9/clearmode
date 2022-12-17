<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if a User can create a Team.
     *
     * @return void
     */
    public function testCreateTeam()
    {
        $user = User::Factory()->create();

        $this->actingAs($user)
            ->post('/teams', [
                'name' => 'Test Team'
            ])
            ->assertOk()
            ->seeJson([
                'success' => true
            ]);


    }
}
