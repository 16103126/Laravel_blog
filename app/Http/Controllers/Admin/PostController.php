<?php

namespace App\Http\Controllers\Admin;

use Image;
use App\Tag;
use App\Post;
use App\Category;
use App\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewPostNotify;
use Illuminate\Support\Str;
use App\Notifications\AuthorPostApproved;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('admin.post.index', compact('posts'));
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
        return view('admin.post.create', compact('categories', 'tags'));
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
            'image' => 'required|mimes:png,jpg,jpeg',
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


        $post->is_approved = true;
        
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        $subscribers = Subscriber::all();
        foreach($subscribers as $subscriber)
        {
            Notification::route('mail', $subscriber->email)
                        ->notify(new NewPostNotify($post));
        }

        Toastr::success('Post Successfully saved', 'success');

        return redirect()->route('admin.post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.show', compact('post', 'categories', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.edit', compact('post','categories', 'tags'));
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
        $this->validate($request,[
            'title' => 'required',
            'image' => 'mimes:jpg,png,jpeg',
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
        

        $post->is_approved = true;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('Post Successfully updated', 'success');
        return redirect()->route('admin.post.index');
    }

    public function pending()
    {
        $posts = Post::where('is_approved', false)->get();
        return view('admin.post.pending', compact('posts'));
    }

    public function approve($id)
    {
        $post = Post::find($id);
        if($post->is_approved == false)
        {
            $post->is_approved = true;
            $post->save();

            $post->user->notify(new AuthorPostApproved($post));

            $subscribers = Subscriber::all();
            foreach($subscribers as $subscriber)
            {
                Notification::route('mail', $subscriber->email)
                            -> notify(new NewpostNotify($post));
            }

            Toastr::success('Post successfully Approved:)', 'Success');
        }else{
            Toastr::info('This post is already approved:)', 'info');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
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
