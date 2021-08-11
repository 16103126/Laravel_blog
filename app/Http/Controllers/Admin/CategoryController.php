<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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
            'name' => 'required|unique:categories',
            'image' => 'required|mimes:jpg, png, jpeg, bmp',
        ]);

        //get form image
        $image = $request->file('image');
        $slug = str_slug($request->name);

        if(isset($image))
        {
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //check category directory for is exists
            if(!Storage::disk('public')->exists('category/'))
            {
                Storage::disk('public')->makeDirectory('category/');
            }

            //resize image for category and upload
            $category = Image::make($image)->resize(1000, 479)->save();
            Storage::disk('public')->put('category/', $imagename, $category);

            //check category slider directory is exists
            if(!Storage::disk('public')->exists('category/slider/'))
            {
                Storage::disk('public')->makeDirectory('category/slider/');
            }

            //resize image for category slider and upload
            $slider = Image::make($image)->resize(500, 333)->save();
            Storage::disk('public')->put('category/slider/', $imagename, $slider);

        }else{
            $imagename = 'default.png';
        }

        $categories = new Category();
        $categories->name = $request->name;
        $categories->slug = $slug;
        $categories->image = $imagename;
        $categories->save();

        Toastr::success('Category Successfully Save', 'success');

        return redirect()->route('admin.category.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
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
        $this->validate($request,[
            'name' => 'required',
            'image' => 'mimes:jpg, png, jpeg, bmp',
        ]);

        //get form image
        $image = $request->file('image');
        $slug = str_slug($request->name);
        $category = Category::find($id);

        if(isset($image))
        {
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //check category directory for is exists
            if(!Storage::disk('public')->exists('category/'))
            {
                Storage::disk('public')->makeDirectory('category/');
            }

            //delete old category image
            if (Storage::disk('public')->exists('category/', $category->image))
            {
                Storage::disk('public')->delete('category/', $category->image);
            }

            //resize image for category and upload
            $categoryimage = Image::make($image)->resize(1000, 479)->save();
            Storage::disk('public')->put('category/', $imagename, $categoryimage);

            //check category slider directory is exists
            if(!Storage::disk('public')->exists('category/slider/'))
            {
                Storage::disk('public')->makeDirectory('category/slider/');
            }

            //delete old category image
            if (Storage::disk('public')->exists('category/slider/'.$category->image))
            {
                Storage::disk('public')->delete('category/slider/'.$category->image);
            }

            //resize image for category slider and upload
            $slider = Image::make($image)->resize(500, 333)->save();
            Storage::disk('public')->put('category/slider/', $imagename, $slider);

        }else{
            $imagename = $category->image;
        }

        $categories = new Category();
        $categories->name = $request->name;
        $categories->slug = $slug;
        $categories->image = $imagename;
        $categories->save();

        Toastr::success('Category Successfully Updated', 'success');

        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (Storage::disk('public')->exists('category/'.$category->image))
        {
            Storage::disk('public')->delete('category/'.$category->image);
        }

        if (Storage::disk('public')->exists('category/slider/'.$category->image))
        {
            Storage::disk('public')->delete('category/slider/'.$category->image);
        }

        $category->delete();
        Toastr::success('Category Successfully deleted', 'success');
        return redirect()->back();
    }
}
