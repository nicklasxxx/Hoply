<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\suportclass\TimeConverter;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        return view('home');
    }


    private function setup()
    {
        $users = json_decode(User::getAPI() );

        foreach($users as $user)
        {
            DB::table('users')->insert(
            [
            'id' => $user->id, 
            'name' => $user->name, 
            'created_at' => TimeConverter::convert($user->stamp), 
            'updated_at' => TimeConverter::convert($user->stamp) 
            ]);
        }

    }



    
}
