<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    // GET /api/posts — public, anyone can read
public function index(Request $request)
{
    $request->validate([
        'per_page' => 'nullable|integer|min:1|max:100',
        'offset'   => 'nullable|integer|min:0',
    ]);

    $perPage = $request->json('per_page', 10);
    $offset  = $request->json('offset',  0);

    $posts = Post::with('user')
                 ->latest()
                 ->skip($offset)
                 ->take($perPage)
                 ->get();

    return response()->json([
        'data'     => PostResource::collection($posts),
        'total'    => Post::count(),
        'per_page' => (int) $perPage,
        'offset'   => (int) $offset,
        'showing'  => $posts->count(),
    ]);
}
    // GET /api/posts/{post} — public
    public function show(Post $post)
    {
        // Route model binding automatically 404s if post not found
        $post->load('user');

        return new PostResource($post);
    }

    // POST /api/posts — auth required
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'body'  => 'required|string',
        ]);

        // Attach authenticated user — never trust user_id from request
        $post = Post::create([
            'title'   => $data['title'],
            'body'    => $data['body'],
            'user_id' => Auth::id(),
        ]);

        $post->load('user');

        return (new PostResource($post))
            ->response()
            ->setStatusCode(201);
    }

    // PUT /api/posts/{post} — auth + ownership required
    public function update(Request $request, Post $post)
    {
        // Only the owner can update — Gate uses PostPolicy
        Gate::authorize('update', $post);

        $data = $request->validate([
            'title' => 'sometimes|string|max:200',
            'body'  => 'sometimes|string',
        ]);

        $post->update($data);
        $post->load('user');

        return new PostResource($post);
    }

    // DELETE /api/posts/{post} — auth + ownership required
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return response()->json([
            'message' => 'Post deleted.',
        ]);
    }
}