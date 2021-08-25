<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = User::authors()
            ->withCount('posts')
            ->withCount('comments')
            ->withCount('favourite_posts')
            ->get();
            return view('admin.author', compact('authors'));
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        
        Toastr::success('Author Successfully Deleted', 'success');
        return redirect()->back();
    }
}
