<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tag = new Tag();

        return response()->json($tag->paginate(5));
    }
}
