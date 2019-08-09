<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\suportclass\Setup;
use App\User;
use App\Follow;
use Auth;


class UsersController extends Controller
{
     
     // Contructor. 
    public function __construct()
    {   
        $this->middleware('UserExist');
        $this->middleware('auth');
    }

    
    /*
    * show all the users. 
    */
    public function index()
    {
      
        $users = DB::select('select id as secret, global_id as id, name, created_at, CONCAT(if(global_id IN (select followee from follows where follower = "'.Auth::user()->global_id.'"), "follows", "follow"  )) AS follows,
        CONCAT(if(global_id IN (select follower from follows where followee = "'.Auth::user()->global_id.'"), "Following you", "")) AS following from users');
        return view('Users.index', compact('users'));
    }


    /*
     *  show a specific user. 
     */
    public function show(int $id)
    {
        $user = User::where('id', $id)->firstOrFail();
        if( !User::userExistOrDelete($user) ) { return redirect()->back();  }

        $followsCount = DB::select('SELECT COUNT(*) as follows from follows where follower = "'.$user->global_id.'"');
        $followingsCount = DB::select('SELECT COUNT(*) as followings from follows where followee = "'.$user->global_id.'"');
        $follows = DB::select('select id as secret, global_id, name from users where global_id IN (SELECT followee from follows where follower = "'.$user->global_id.'")');
        $followings = DB::select('select id as secret, global_id, name from users where global_id IN (SELECT follower from follows where followee = "'.$user->global_id.'")');
        $currentUserFollowing = DB::table('follows')->where('follower', Auth::user()->global_id)->where('followee', $user->global_id)->first();
        return view('Users.show', compact('followsCount', 'followingsCount', 'follows', 'followings', 'user', 'currentUserFollowing'));

    }


    /*
     * post method, check for new users, and sync with local database. 
     */
    public function postUpdate()
    {
        User::checkForNewUsers(); // check for new users, before showing page. 
        return redirect()->back();
    }

     /*
     * Update a specific user info, name and followers/following.  
     */
    public function putUpdate(int $id)
    {   
        $user = User::where('id', $id)->firstOrFail();
        Follow::updateUserFollow($user); // update who the user follows, and who follows the users. 
        return redirect()->back();
    }




}
