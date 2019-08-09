<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\suportclass\TimeConverter;

class Follow extends Model
{

	public static function getGlobalAll()
	{
	   return  Rest::GET('http://caracal.imada.sdu.dk/app2019/follows');
	}



	
	public static function GlobalInsert($follower, $followee) 
	{

	 $data = '{"follower":"'.$follower.'","followee":"'.$followee.'"}';

	 $respons = Rest::POST('http://caracal.imada.sdu.dk/app2019/follows', $data);

	    if(gettype($respons) == 'integer' && $respons == 201)
	    {	
	    	
	        return json_encode($respons);
	        return true;
	    }else{

	        return json_encode($respons);
	        return false; 
	    }

	} 


	public static function GlobalDelete($follower, $followee) 
	{

	 $data = '{"follower":"'.$follower.'","followee":"'.$followee.'"}';

	 $respons = Rest::deleteGlobal('http://caracal.imada.sdu.dk/app2019/follows?follower=eq.'.$follower.'&followee=eq.'.$followee.'', $data);

	    if(gettype($respons) == 'integer' && $respons == 201)
	    {
	        
	        return true;
	    }else{

	    	//return $respons;
	        return false; 
	    }

	}

	/*
	 * Update a specific users followers, followee info. 
	 */
	public static function updateUserFollow($user)
	{
		DB::table('Follows')->where('follower', $user->global_id)->orWhere('followee', $user->global_id)->delete();
		$followers = json_decode(Rest::GET('http://caracal.imada.sdu.dk/app2019/follows?follower=eq.'.$user->global_id.''));
		$followees = json_decode(Rest::GET('http://caracal.imada.sdu.dk/app2019/follows?followee=eq.'.$user->global_id.''));
		

		foreach ($followers as $value) 
		{	
			$timestamp = TimeConverter::convert($value->stamp);
			$follows = new Follow();
			$follows->follower = $value->follower;
			$follows->followee = $value->followee;
			$follows->created_at = $timestamp;
			$follows->updated_at = $timestamp;
			$follows->save();
			
		}

		foreach ($followees as $value) 
		{
			$timestamp = TimeConverter::convert($value->stamp);
			$follows = new Follow();
			$follows->follower = $value->follower;
			$follows->followee = $value->followee;
			$follows->created_at = $timestamp;
			$follows->updated_at = $timestamp;
			$follows->save();	
		}

		
	} 

}
