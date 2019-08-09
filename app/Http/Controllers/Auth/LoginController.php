<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User; 
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {

        login as protected traitlogin;
    } 
    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }


    public function getlogin()
    {
        return view('auth.login');
    }



    public function login(Request $request)
    {
        $email = $request->email;
        $password = "123";

        $user = User::where('email', $request->email)->orWhere('global_id', $request->email)->first();
            
           // return gettype($user);        

        if ($request->password == $password && $user != null ) {
            // Authentication passed...
             Auth::login($user);

            return redirect('/home');
            
        }else{
            $errors = (['email' => "Email og adgangskode matcher ikke", 'oldemail' => $email]);
            return back()->withErrors($errors);
        }
        
    }


public function logout()
{

    Auth::logout();
    return redirect('/');

}



}





