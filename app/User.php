<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Log;
use App\suportclass\TimeConverter;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'name', 'email', 'password', 'global_id',
    ];

    



public static function getGlobalAll()
{
   return  Rest::GET('http://caracal.imada.sdu.dk/app2019/users');
}


/*
* return a boolean either a user is created at the global databse or not. 
*/
public static function createUser($id, $name)
{

 $data = '{"id":"'.$id.'","name":"'.$name.'"}';
 $respons = Rest::POST('http://caracal.imada.sdu.dk/app2019/users', $data);

    if(gettype($respons) == 'integer' && $respons == 201)
    {
        return true;
    }else{

        //return json_encode($respons->code);
        return false; 
    }
}

    public static function updateUser($id, $name)
    {

         $year =  date("Y-m-d"); 
        $day = date("G:i:s+02:00");
        $stamp = $year."T".$day;

        $data = '{"id":"'.$id.'","name":"'.$name.'", "stamp": "'.$stamp.'" }';
        $respons = Rest::PUT('http://caracal.imada.sdu.dk/app2019/users?id=eq.'.$id.'', $data);

    if(gettype($respons) == 'integer' && $respons == 204)
    {
        // return 'created';
        return true;
    }else{

        //return json_encode($respons);
        return false; 
    }

    }


    public static function checkForNewUsers()
    {   
        $userTimeStamp = DB::select('Select * from users ORDER BY created_at desc limit 1')[0]->created_at;
        $userTimeStamp = str_replace(" ","T",$userTimeStamp);
        $newUsers = json_decode(Rest::GET('http://caracal.imada.sdu.dk/app2019/users?stamp=gt.'.$userTimeStamp.''));
        self::storeNewUsers($newUsers);
    }


/*
* the logic of insert a new user, 
* @parameter Std classs of users. 
*/
public static function storeNewUsers($newUsers)
{
    foreach($newUsers as $user)
    {   
        $str = substr($user->name, 0, 3);
        if($str != "%GRP") // This app, dont support group messages, so groups shall not been show as users. 
        {
            $timestamp = TimeConverter::convert($user->stamp);
            DB::table('users')->insert(
            ['global_id' => $user->id, 'name' => $user->name, 'created_at' => $timestamp, 'updated_at' => $timestamp] );
        }
    }
}

/*
* Check if a user still exist, 
* @parameter user global_id.
*/
public static function checkUserExisit($userid) 
{
    $newUsers = json_decode(Rest::GET('http://caracal.imada.sdu.dk/app2019/users?id=eq.'.$userid.''));

    return !empty($newUsers);
}


/*
 * check if user exist, if not, delete it and et followings/follows from local database. 
 */
public static function userExistOrDelete($user)
{   
 
    if(self::checkUserExisit($user->global_id) )
    {
        return true;
    }else{
        // not working 100%
        /*
        DB::table('messages')->where('sender', $user->global_id)->OrWhere('receiver', $user->global_id)->delete();
        DB::table('follows')->where('follower', $user->global_id)->OrWhere('followee', $user->global_id)->delete();
        $user->delete();
        */
        return false;


    }

}







}
