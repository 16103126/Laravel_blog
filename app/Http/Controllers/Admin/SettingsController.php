<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {   $user = User::get();
        return view('admin.settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'required',
        ]);

        // $slug = str_slug($request->name);
        $user = User::findOrFail(Auth::id());
        
        if($file = $request->file('image')){
            
            $userimg = Image::make($file->getRealPath())->resize(500,500);
            $imageName = time().Str::random(8).'.jpg';
           
            $userimg->save(public_path().'/assets/backend/images/profile/'.$imageName);
            if($user->image){
                
                if(file_exists(public_path().'/assets/backend/images/profile/'.$user->image)){
                    @unlink(public_path().'/assets/backend/images/profile/'.$user->image);
                }  
            }
            $user->image = $imageName;
            // dd($user);               
        }
        

        $user->name = $request->name;
        // $user->slug = $slug;
        $user->email = $request->email;
        $user->about = $request->about;
        $user->save();

        Toastr::success('User Profile Successfully Updated', 'success');

        return redirect()->back();
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request,[
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        $hashedPassword = Auth::user()->password;
        if(Hash::check($request->old_password, $hashedPassword))
        {
            if(!Hash::check($request->password, $hashedPassword))
            {
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();
                Toastr::success('Password Changed successfully.', 'success');
                Auth::logout();
                return redirect()->back();
            }else{
                Toastr::error('New password can not be the same as old password', 'Error');
                return redirect()->back();
            }
        }else{
            Toastr::error('Current password not match', 'Error');
            return redirect()->back();
        }
    }
}
