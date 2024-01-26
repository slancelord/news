<?php

namespace App\Http\Controllers;

use \Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Resources\NewsResource;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::query();

        try{
            $validated = $request->validate([
                'title' => 'string',
                'tags' => 'array',
                'tags.*' => 'integer|exists:tags,id'
            ]);
        } catch(ValidationException  $error) {
            return response($error->errors(), 400);
        }

        if($request->has('tags')) 
        {
            $tags = $request->input('tags');
            $news = $news->whereHas('tags', function($query) use ($tags) {
                $query->whereIn('tag_id', $tags);
            });
        }

        if($request->has('title')) 
        {
            $news = $news->where('title', 'like', '%' . $request->input('title') . '%');
        }

        return NewsResource::collection($news->paginate(3));
    }


    
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'title' => 'required|string',
                'content' => 'required|string',
                'user_id' => 'required|integer|exists:users,id',
                'tags' => 'required|array',
                'tags.*' => 'required|integer|exists:tags,id'
            ]);
        } catch(ValidationException  $error) {
            return response($error->errors(), 400);
        }
        
        $news = News::create($validated);
        $news->tags()->sync($validated['tags']);

        return response(new NewsResource($news), 201);
    }


    public function show(string $id)
    {
        $news = News::findOrFail($id);
        
        return new NewsResource($news);
    }


    public function update(Request $request, string $id)
    {
        $news = News::findOrFail($id);
        
        try{
            $validated = $request->validate([
                'title' => 'required|string',
                'content' => 'required|string',
                'user_id' => 'required|integer|exists:users,id',
                'tags' => 'required|array',
                'tags.*' => 'required|integer|exists:tags,id'
            ]);
        } catch(ValidationException  $error) {
            return response($error->errors(), 400);
        }

        $news->update($validated);
        $news->tags()->sync($validated['tags']);

        return response(new NewsResource($news), 201);
    }


    public function destroy(string $id)
    {
        $news = News::findOrFail($id);

        return response($news->delete(), 201);
    }

    public function restore(string $id)
    {
        $news = News::withTrashed()->findOrFail($id);

        return response($news->restore(), 201);
    }
}
