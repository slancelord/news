<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = new News();

        if($request->has('tag')) 
        {
            $tags = explode(',', $request->input('tag'));
            $news = $news->whereHas('tags', function($query) use ($tags) {
                $query->whereIn('tag_id', $tags);
            });
        }

        if($request->has('title')) 
        {
            $titles = explode(',', $request->input('title'));
            $news = $news->whereIn('title', $titles);
        }

        $news = $news->paginate(3);

        $news->getCollection()->transform(function ($el) {
            $el->description = Str::limit($el->content, 100);
            $el->tags = $el->tags()->pluck('tag_id')->toArray();
            unset($el->content);
            return $el;
        });

        return response()->json($news);
    }


    
    public function store(Request $request)
    {
        $news = new News();

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:News|string',
            'content' => 'required|string',
            'user_id' => 'required|integer|exists:App\Models\User,id',
            'tags' => 'required|array'
        ]);
        
        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 400);
        }

        $validated = $validator->validated();
        
        $news = News::create($validated);
        $news->tags()->attach($validated['tags']);
        $news->tags = $news->tags()->pluck('tag_id')->toArray();

        return response()->json($news, 201);
    }


    public function show(string $id)
    {
        $news = News::findOrFail($id);
        $news->tags = $news->tags()->pluck('tag_id')->toArray();
        
        return response()->json($news);
    }


    public function update(Request $request, string $id)
    {
        $news = News::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',  Rule::unique('users')->ignore($id),
            'content' => 'required|string',
            'user_id' => 'required|integer|exists:App\Models\User,id',
            'tags' => 'required|array'
        ]);

        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 400);
        }

        $validated = $validator->validated();

        $news->update($validated);
        $news->tags()->sync($validated['tags']);
        $news->tags = $news->tags()->pluck('tag_id')->toArray();

        return response()->json($news, 201);
    }


    public function destroy(string $id)
    {
        $news = News::findOrFail($id);

        return response()->json($news->delete(), 201);
    }

    public function restore(string $id)
    {
        $news = News::withTrashed()->findOrFail($id);

        return response()->json($news->restore(), 201);
    }
}
