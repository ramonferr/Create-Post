<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
// Use the Post Model
use Illuminate\Http\Request;
// We will use Form Request to validate incoming requests from our store and update method
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(): Response
    {
        return response()->view('posts.index', [
            'posts' => Post::orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function create(): Response
    {
        return response()->view('posts.form');
    }

    public function store(StoreRequest $request): RedirectResponse // oque está sendo passado como parametro nesse metodo se injecao de dependencia
    {
        $validated = $request->validated();

        if ($request->hasFile('featured_image')) {
            $filePath = Storage::disk('public')->put('images/posts/featured-images', request()->file('featured_image'));
            $validated['featured_image'] = $filePath;
        }

        $create = Post::create($validated);

        if ($create) {
            session()->flash('notif.success', 'Post created successfully!');

            return redirect()->route('posts.index');
        }

        return abort(500);
    }

    public function show(string $id): Response // parametro
    {
        return response()->view('posts.show', [
            'post' => Post::findOrFail($id), // e necessário passar o id para que ele identifique quem é o dono do post
        ]);
    }

    public function edit(string $id): Response
    {
        return response()->view('posts.form', [
            'post' => Post::findOrFail($id),
        ]);
    }

    public function update(UpdateRequest $request, string $id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('featured_image')) {
            Storage::disk('public')->delete($post->featured_image);

            $filePath = Storage::disk('public')->put('images/posts/featured-images', request()->file('featured_image'), 'public');
            $validated['featured_image'] = $filePath;
        }

        $update = $post->update($validated);

        if ($update) {
            session()->flash('notif.success', 'Post updated successfully!');

            return redirect()->route('posts.index');
        }

        return abort(500);
    }

    public function destroy(string $id): RedirectResponse
    {
        $post = Post::findOrFail($id);

        Storage::disk('public')->delete($post->featured_image);

        $delete = $post->delete($id);

        if ($delete) {
            session()->flash('notif.success', 'Post deleted successfully!');

            return redirect()->route('posts.index');
        }

        return abort(500);
    }
}
