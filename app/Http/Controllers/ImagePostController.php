<?php

namespace App\Http\Controllers;

use App\Models\ImagePost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ImagePostController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  string  $userSlug
     * @param  string  $imagePostId
     * @param  string  $imagePostSlug
     * @param Request $request
     *
     * @return View
     */
    public function show(string $userSlug, string $imagePostId, string $imagePostSlug, Request $request):View
    {
        $imagePost = ImagePost::findOrFail($imagePostId);

        return view('image-post.show', [
            'user' => $request->user(),
            'imagePost' => $imagePost,
        ]);
    }

    /**
     * Create new ImagePost
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request):Response{

        $user = Auth::user();
        $imageFile = $request->file('image_file');
        $url=$imageFile->storePublicly($user->slug."/img");

        $imagePost = new ImagePost();
        $imagePost->title = $request->title;
        $imagePost->url = $url;
        $imagePost->save();

        return new Response();
    }
}
