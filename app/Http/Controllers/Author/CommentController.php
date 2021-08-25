<?php

namespace App\Http\Controllers\Author;

use App\Comment;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    Public function index()
    {
        $posts = Auth::user()->posts;
        return view('author.comment', compact('posts'));
    }

    Public function destroy($id)
    {
    $comment = Comment::findOrFail($id);
    If($comment->post->user->id == Auth::id())
    {
    $comment->delete();
    Toastr::success('Comment Successfully Deleted', 'success');
    }else{
    Toastr::success('You are not authorized to delete this comment:(','Access Denied !!!');
    }
    return redirect()->back();
    }
}
