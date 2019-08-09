<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\suportclass\Setup;
use App\User;
use App\Follow;
use Auth;

class FollowController extends Controller
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
    * @param $id, is a user local id. 
     * Update the Auth users, follower/followee'
    **/
    public function update(int $id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $check = DB::table('follows')->where('follower', Auth::user()->global_id)->where('followee', $user->global_id)->first();

        if($check != null)
        {   
            Follow::GlobalDelete(Auth::user()->global_id, $user->global_id);
            DB::statement('delete from follows where follower = "'.Auth::user()->global_id.'" AND followee = "'.$user->global_id.'"');

        }else{

            // update Global database. 
          if(Follow::GlobalInsert(Auth::user()->global_id, $user->global_id) )
           { 
             $follow = new Follow();
             $follow->follower = Auth::user()->global_id;
             $follow->followee = $user->global_id; 
             $follow->save();
            } 
        }
    	return redirect()->back();
    }
}
