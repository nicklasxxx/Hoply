<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\suportclass\Setup;
use App\User;
use App\Follow;
use Auth;

class WelcomeController extends Controller
{
    
	/*
	* welcome page. 
	*/
    public function index()
    {
    	return view('welcome');
    }
}
