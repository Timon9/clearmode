<?php

namespace Tests\Unit;

use App\Models\ImagePost;

class ImagePostTest extends PostTestCase
{
    public function __construct()
    {
        parent::__construct(new ImagePost());
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
        $fillable = $this->model->getFillable();
        $this->assertContains('url', $fillable);
    }
}
