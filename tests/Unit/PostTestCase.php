<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTestCase extends TestCase
{
    protected Model $model;

    use WithFaker;

    /**
     * Test if we can set a title
     *
     * @return void
     */
    public function test_post_model_has_a_title_attribute()
    {
        // Arrange
        $title = $this->faker->sentence();
        // Act
        $this->model->title = $title;
        // Assert
        $this->assertEquals($title, $this->model->title);
    }

}
