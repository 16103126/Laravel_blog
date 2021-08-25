<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use App\Tagimage;
use Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::latest()->get();
        return view('admin.tag.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->slug = Str_slug($request->name);
        $tag->save();

        // if (count($request->tag_image)>0)
        // {
        //     //if($request->hasFile('tag_image'))
        //     //{
        //         foreach($request->tag_image as $image)
        //         {
                    
        //            //insert that image
        //         // $image = $request->file('tag_image');
        //         $img = time().'.'.$image->getClientOriginalExtension();
        //         $location = public_path('images/tags/'.$img);
        //         Image::make($image)->save($location);
        //         $tag_image = new Tagimage;
        //         $tag_image->tag_id = $tag->id;
        //         $tag_image->image = $img;
        //         $tag_image->save();  
        //         }
        // }

        if ($request->hasfile('images')) {
            $images = $request->file('images');

            foreach($images as $image) {
                $img = time().'.'.$image->getClientOriginalExtension();
                $location = public_path('images/tags/'.$img);
                Image::make($image)->save($location);
                $insert[] = $img;

                Tagimage::create([
                    'tag_id' => $tag->id,
                    'image' => $img,
                  ]);
            }
         }

        //  if($request->hasFile('image')) {
        //     foreach($request->file('image') as $image) {
        //         $destinationPath = 'content_images/';
        //         $filename = $image->getClientOriginalName();
        //         $image->move($destinationPath, $filename);
        
        //     $allImagesPathes[ ]['path'] = $filename;
        
        //     }
        //     $content->images()->createMany($allImagesPathes); // this will save you tens of DB quires
        // }

        Toastr::success('Tag Successfully Save!', 'success');

        return redirect()->route('admin.tag.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);
        return view('admin.tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $tag = Tag::find($id);
        $tag->name = $request->name;
        $tag->slug = Str_slug($request->name);
        $tag->save();

        Toastr::success('Tag Successfully Updated!', 'success');

        return redirect()->route('admin.tag.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tag::find($id)->delete();
    }
}
