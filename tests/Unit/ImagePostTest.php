<?php

namespace Tests\Unit;

use App\Models\ImagePost;

class ImagePostTest extends PostTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ImagePost();
    }
    /**
     * Basic test will be done in the parent class PostTest.
     *
     * You can add ImagePost specific unit test below.
     */
        /**
     * Test if we can add an image URL
     *
     * @return void
     */
    public function test_image_post_have_url_attribute()
    {
        // Arrange
        $url = $this->faker->imageUrl();
        // Act
        $this->model->url = $url;
        // Assert
        $this->assertEquals($url, $this->model->url);
    }

}
