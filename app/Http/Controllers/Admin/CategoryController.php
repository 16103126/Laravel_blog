<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'image' => 'required|mimes:jpg,png,jpeg,bmp',
        ]);

        $slug = str_slug($request->name);

        if($file = $request->file('image')){
            $categoryimg = Image::make($file->getRealPath())->resize(1000,479);
            $sliderimg = Image::make($file->getRealPath())->resize(500,333);
            $imageName = time().Str::random(8).'.jpg';
            $categoryimg->save(public_path().'/assets/backend/images/category/'.$imageName);
            $sliderimg->save(public_path().'/assets/backend/images/slider/'.$imageName);         
        }

        $categories = new Category();
        $categories->name = $request->name;
        $categories->slug = $slug;
        $categories->image = $imageName;
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
            'image' => 'mimes:jpg,png,jpeg,bmp',
        ]);
        $categories = Category::find($id);

        $slug = str_slug($request->name);
        
        if($file = $request->file('image')){
            
            $categoryimg = Image::make($file->getRealPath())->resize(1000,479);
            $sliderimg = Image::make($file->getRealPath())->resize(500,333);
            $imageName = time().Str::random(8).'.jpg';
           
            $categoryimg->save(public_path().'/assets/backend/images/category/'.$imageName);
            $sliderimg->save(public_path().'/assets/backend/images/slider/'.$imageName);

            if($categories->image){
                
                if(file_exists(public_path().'/assets/backend/images/category/'.$categories->image)){
                    @unlink(public_path().'/assets/backend/images/category/'.$categories->image);
                }

                if(file_exists(public_path().'/assets/backend/images/slider/'.$categories->image)){
                    @unlink(public_path().'/assets/backend/images/slider/'.$categories->image);
                }    
            }
            $categories->image = $imageName;               
        }

        $categories->name = $request->name;
        $categories->slug = $slug;
       
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
        if (file_exists(public_path().'/assets/backend/images/category/'.$category->image))
        {
            @unlink(public_path().'/assets/backend/images/category/'.$category->image);
        }

        if (file_exists(public_path().'/assets/backend/images/slider/'.$category->image))
        {
            @unlink(public_path().'/assets/backend/images/slider/'.$category->image);
        }

        $category->delete();
        Toastr::success('Category Successfully deleted', 'success');
        return redirect()->back();
    }
}
