<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test the avatar function.
     *
     * @return void
     */
    public function testWeCanGenerateAnAvatarForTheUser()
    {
        $user = new User();

        // Test the default parameters
        $avatar = $user->avatar();
        $this->assertIsString($avatar);
        $this->assertStringStartsWith('data:image/png;base64,', $avatar);

        // Test with custom size
        $avatar = $user->avatar(80, 80, 50);
        $this->assertIsString($avatar);
        $this->assertStringStartsWith('data:image/png;base64,', $avatar);
    }
}
