<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rest; 

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * return the view to register a new user. 
     *
     */
     public function getregister()
     {
        return view('auth.register');
     }

     /*
     * is the most method for register a new user. 
     */
    public function register(Request $request)
    {   
        $validatedData = $request->validate([
          'id' => 'required|string|max:191',
          'name' => 'required|string|max:191',
         ]);

        // create a user in the  global database if possible
        // return false if not. 
        if( User::createUser($request->id, $request->name) ) 
        {
              $user = $this->create($request);
              Auth::login($user);
              return redirect('/home');

        }else{

            
            return redirect()->back()->with('error', 'User already exist');
        }



      

    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {
        return User::create([
            'global_id' => $request->id,
            'name' => $request->name
            
        ]);
    }
}
