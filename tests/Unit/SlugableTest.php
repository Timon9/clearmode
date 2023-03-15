<?php

namespace Tests\Unit;

use App\Traits\Slugable;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use stdClass;
use Tests\TestCase;
use Illuminate\Support\Str;

class SlugableTest extends TestCase
{
    use WithFaker;

    /**
     * Test if we can create a slug
     *
     * @return void
     */
    public function test_we_can_create_a_slug()
    {
        $mockedResponse = $this->mock(stdClass::class,function(MockInterface $mockInterface){
            $mockInterface->expects("count")->once()->andReturn(0);
        });

        /**
         * @var Slugable $mock
         */
        $mock = $this->mock(Slugable::class,function(MockInterface $mockInterface) use ($mockedResponse){
            $mockInterface->expects('where')->once()->andReturn($mockedResponse);
        });

        $name = $this->faker->name();
        $this->assertEquals(Str::slug($name),$mock->createSlug($name));

    }


     /**
     * Test if we can create a slug that increments when a duplicate slug is found
     *
     * @return void
     */
    public function test_we_can_create_a_increment_slug()
    {
        $count = $this->faker->numberBetween(3,999);
        $mockedResponse = $this->mock(stdClass::class,function(MockInterface $mockInterface) use ($count){
            $mockInterface->expects("count")->once()->andReturn($count);
        });

        /**
         * @var Slugable $mock
         */
        $mock = $this->mock(Slugable::class,function(MockInterface $mockInterface) use ($mockedResponse){
            $mockInterface->expects('where')->once()->andReturn($mockedResponse);
        });

        $name = $this->faker->name();
        $this->assertEquals(Str::slug($name)."-".(++$count),$mock->createSlug($name));

    }

    public function test_slugs_should_be_valid_urls(){
        $mockedResponse = $this->mock(stdClass::class,function(MockInterface $mockInterface){
            $mockInterface->expects("count")->once()->andReturn(0);
        });

        /**
         * @var Slugable $mock
         */
        $mock = $this->mock(Slugable::class,function(MockInterface $mockInterface) use ($mockedResponse){
            $mockInterface->expects('where')->once()->andReturn($mockedResponse);
        });

        $slug = $mock->createSlug($this->faker->name());
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9\-_]{2,}$/',$slug);
    }
}
