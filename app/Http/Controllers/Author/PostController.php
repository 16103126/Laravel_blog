<?php

namespace App\Http\Controllers\Author;

use App\Tag;
use App\Post;
use App\User;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewAuthorPost;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::user()->posts()->latest()->get();
        return view('author.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();
        $tags = Tag::get();
        return view('author.post.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'image' => 'required',
            'body'  => 'required',
            'tags'  => 'required',
            'categories' => 'required',
        ]);
        
        $slug = str_slug($request->title);

        if($file = $request->file('image')){
            $postimg = Image::make($file->getRealPath())->resize(1600,1066);
            $imageName = time().Str::random(8).'.jpg';
            $postimg->save(public_path().'/assets/backend/images/post/'.$imageName);     
        }

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;

        if(isset($request->status))
        {
            $post->status = true;
        }else{
            $post->status = false;
        }

        $post->is_approved = false;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        $users = User::where('role_id', '1')->get();
        Notification::send($users, new NewAuthorPost($post));

        Toastr::success('Post Successfully saved', 'success');
        return redirect()->route('author.post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if($post->user_id != Auth::id())
        {
            Toastr::error('You are not authorized to access this post', 'Error');
            return redirect()->back();
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.show', compact('post', 'categories', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if($post->user_id != Auth::id())
        {
            Toastr::error('You are not authorized to access this post', 'Error');
            return redirect()->back();
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.edit', compact('post','categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if($post->user_id != Auth::id())
        {
            Toastr::error('You are not authorized to access this post', 'Error');
            return redirect()->back();
        }

        $this->validate($request,[
            'title' => 'required',
            'image' => 'image',
            'body'  => 'required',
            'tags'  => 'required',
            'categories' => 'required',
        ]);
  
        $slug = str_slug($request->title);

        if($file = $request->file('image')){
            
            $postimg = Image::make($file->getRealPath())->resize(1000,479);
            $imageName = time().Str::random(8).'.jpg';
           
            $postimg->save(public_path().'/assets/backend/images/post/'.$imageName);

            if($post->image){
                
                if(file_exists(public_path().'/assets/backend/images/post/'.$post->image)){
                    @unlink(public_path().'/assets/backend/images/post/'.$post->image);
                }  
            }
            $post->image = $imageName;               
        }

        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->body = $request->body;

        if(isset($request->status))
        {
            $post->status = true;
        }else{
            $post->status = false;
        }

        $post->is_approved = false;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);
        
        Toastr::success('Post Successfully updated', 'success');
        return redirect()->route('author.post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if($post->user_id != Auth::id())
        {
            Toastr::error('You are not authorized to access this post', 'Error');
            return redirect()->back();
        }

        if (file_exists(public_path().'/assets/backend/images/post/'.$post->image))
        {
            @unlink(public_path().'/assets/backend/images/post/'.$post->image);
        }

        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();

        Toastr::success('Post Successfully deleted', 'success');
        return redirect()->back();
    }
}
