<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::paginate(3);

        $news->getCollection()->transform(function ($el) {
            $el->description = Str::limit($el->content, 100);
            $el->tag = $el->tags()->pluck('tag_id')->toArray();
            unset($el->content);
            return $el;
        });

        return response()->json($news);
    }


    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:News|string',
            'content' => 'required|string',
            'user_id' => 'required|integer',
            'tags' => 'required|array'
        ]);
        
        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 400);
        }
        
        $news = News::create($request->only(['title', 'content', 'user_id']));
        $news->tags()->attach($request->input('tags'));

        return response()->json($news, 201);
    }


    public function show(string $id)
    {
        $news = News::findOrFail($id);
        $news->tag = $news->tags()->pluck('tag_id')->toArray();
        
        return response()->json($news);
    }


    public function update(Request $request, string $id)
    {
        $ns = News::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:News|string',
            'content' => 'required|string',
            'user_id' => 'required|integer',
            'tags' => 'required|array'
        ]);

        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 400);
        }
    
        $ns->tags()->sync($request->input('tags'));
        $ns->update($request->only(['title', 'content', 'user_id']));

        return response()->json($ns, 201);
    }


    public function destroy(string $id)
    {
        $ns = News::findOrFail($id);

        return response()->json($ns->delete(), 201);
    }
}
