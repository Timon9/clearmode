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

    /**
     * Test the getInitialsAttribute function.
     *
     * @return void
     */
    public function testWeCanGenerateInitials()
    {
        $user = new User;

        // Test with two words
        $user->name = 'John Smith';
        $this->assertEquals('JS', $user->getInitialsAttribute());

        // Test with one word
        $user->name = 'John';
        $this->assertEquals('Jo', $user->getInitialsAttribute());

        // Test with three words
        $user->name = 'John Adam Smith';
        $this->assertEquals('JA', $user->getInitialsAttribute());
    }
}
