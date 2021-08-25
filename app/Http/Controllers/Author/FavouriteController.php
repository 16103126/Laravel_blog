<?php

namespace App\Http\Controllers\Author;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavouriteController extends Controller
{
    public function index()
    {
        $posts = Auth::user()->favourite_posts;
        return view('admin.favourite', compact('posts'));
    }
}
