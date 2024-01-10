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
            'user_id' => 'required|integer'
        ]);
        
        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 400);
        }
        
        return response()->json(News::create($request->all()), 201);
    }


    public function show(string $id)
    {
        return response()->json(News::findOrFail($id));
    }


    public function update(Request $request, string $id)
    {
        $ns = News::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:News|string',
            'content' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 400);
        }
        
        return response()->json($ns->update($request->all()), 201);
    }


    public function destroy(string $id)
    {
        $ns = News::findOrFail($id);
        return response()->json($ns->delete(), 201);
    }
}