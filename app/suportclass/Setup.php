<?php

namespace App\suportclass;

use Illuminate\Support\Facades\Auth;
use App\suportclass\TimeConverter;
use Illuminate\Support\Facades\DB;
use App\Message;
use App\Follow;
use App\Rest; 
use App\User;



// get all information from global server to local server: 


Class Setup {


// get all information about users; 
public static function users()
{
	$users = json_decode(User::getGlobalAll() ); 
	User::storeNewUsers($users);
}


// Get all information about messages
public static function messages()
{
	$messages = json_decode(Message::getGlobalAll() ); 
	Message::storeNewMessage($messages);
}

// get all information about follows
public static function follows() 
{
	$follows = json_decode(Follow::getGlobalAll() ); 

	foreach($follows as $follow)
	{	
		$timestamp = TimeConverter::convert($follow->stamp);
		
		DB::table('follows')->insert(
    	['follower' => $follow->follower, 'followee' => $follow->followee, 'created_at' => $timestamp, 'updated_at' => $timestamp] );
	}

	
}










}