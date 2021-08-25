<?php

namespace App\Http\Controllers;

use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function add($post)
    {
        $user = Auth::user();
        $isFavourite = $user->favourite_posts()->where('post_id', $post)->count();
        if($isFavourite == 0)
        {
            $user->favourite_posts()->attach($post);
            Toastr::success('Your post added to the favourite list', 'success');
            return redirect()->back();
        }else{
            $user->favourite_posts()->detach($post);
            Toastr::success('Post successfully removed from fravourite list.', 'succss');
            return redirect()->back();
        }
    }
}
