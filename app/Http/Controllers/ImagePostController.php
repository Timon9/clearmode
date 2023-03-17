<?php

namespace App\Http\Controllers;

use App\Models\ImagePost;
use Illuminate\Http\Request;

class ImagePostController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  string  $userSlug
     * @param  string  $imagePostSlug
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $userSlug, string $imagePostSlug, Request $request)
    {
        $imagePost = ImagePost::where(["slug" => $imagePostSlug])->firstOrFail();

        return view('image-post.show', [
            'user' => $request->user(),
            'imagePost' => $imagePost,
        ]);
    }
}
