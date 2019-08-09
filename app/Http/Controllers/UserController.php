<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\suportclass\Setup;
use App\User;
use App\Follow;
use Auth;

class UserController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('UserExist');
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $followsCount = DB::select('SELECT COUNT(*) as follows from follows where follower = "'.Auth::user()->global_id.'"');
        $followingsCount = DB::select('SELECT COUNT(*) as followings from follows where followee = "'.Auth::user()->global_id.'"');
        $follows = DB::select('select id as secret, global_id, name from users, follows where follows.follower = "'.Auth::user()->global_id.'" AND users.global_id = follows.followee');
        $followings = DB::select('select id as secret, global_id, name from users, follows where follows.followee = "'.Auth::user()->global_id.'" AND users.global_id = follows.follower');

        return view('User.index', compact('followsCount', 'followingsCount', 'follows', 'followings'));
    }


    public function update(Request $request)
    {   
        $validatedData = $request->validate([
        'name' => 'required|string',
        'email' => 'nullable|string|unique:users,email'
    
        ]);

        $name = $request->name; 
        $email = $request->email;

        // return true if succes in update global database. 
        if( User::updateUser(Auth::user()->global_id, $name) )
        {
            $user = User::where('id', Auth::id())->first();
            $user->name = $name; 
            $user->email = $email;
            $user->save();

            Follow::updateUserFollow(Auth::user());
        }



        return redirect()->back();



        

    }


}
