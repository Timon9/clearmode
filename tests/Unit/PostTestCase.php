<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class PostTestCase extends TestCase
{
    protected Model $model;

    use WithFaker, DatabaseMigrations;

    /**
     * Test if we can set a title
     *
     * @return void
     */
    public function test_post_model_has_a_title_attribute()
    {
        $fillable = $this->model->getFillable();
        $this->assertContains('title', $fillable);
    }

    /**
     * Test if we can set a slug automaticly
     *
     * @return void
     */
    public function test_post_model_has_a_slug_created_automaticly()
    {
        // Arrange
        $title = $this->faker->sentence();
        // Act
        $this->model->title = $title;
        // Assert: We expect the slug to be created automaticly.
        $this->assertEquals(Str::slug($title), $this->model->slug);
    }

}
