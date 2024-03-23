<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
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

    public function store(StoreRequest $request) // oque está sendo passado como parametro nesse metodo se injecao de dependencia
    {
        $validated = $request->validated();

        if ($request->hasFile('featured_image')) {
            $filePath = Storage::disk('public')->put('images/posts/featured-images', request()->file('featured_image'));
            $validated['featured_image'] = $filePath;
        }

        Post::create($validated);

        Session::flash('success_message', 'Post criado com sucesso!');

       return redirect()->route('posts.index');
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

        $post->update($validated);

        Session::flash('success_message', 'Post atualizado com sucesso!');

        return redirect()->route('posts.index');
    

    }

    public function destroy(string $id): RedirectResponse
    {
        $post = Post::findOrFail($id);

        Storage::disk('public')->delete($post->featured_image);

        $post->delete($id);

        Session::flash('success_message', 'Post deletado com sucesso!');


        return redirect()->route('posts.index');
    

    }
}
