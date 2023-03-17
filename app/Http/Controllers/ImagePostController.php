<?php

namespace App\Http\Controllers;

use App\Models\ImagePost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    public function show(string $userSlug, string $imagePostId, string $imagePostSlug, Request $request): View
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
    public function create(Request $request): Response
    {

        // $user = Auth::user();

        $imageFile = $request->file('image_file');
        $filePath = $imageFile->storePublicly("img"); // Internal storage path

        $url = "/media/".$filePath; // Publicly accessible url

        $imagePost = new ImagePost();
        $imagePost->title = $request->title;
        $imagePost->url = $url;
        $imagePost->save();

        return new Response();
    }

    /**
     * Retrieve the uploaded image
     *
     * @param string $path
     * @return Response
     */
    public function getImageFile(string $path)
    {
        $path = "img/".$path; // Prefix the image subdirectory

        if (!Storage::exists($path)) {
            abort(404);
        }

        $file = Storage::get($path);

        $headers = [
            'Content-Type' => Storage::mimeType($path),
            'Content-Length' => Storage::size($path),
        ];

        return response()->make($file, 200, $headers);
    }
}
