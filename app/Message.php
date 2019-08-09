<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\suportclass\TimeConverter;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    
/**
 * analyze the method, and return an array of type[0] and message[1]
 *
 */
public static function getType($message) : array
{

    $str = substr($message, 0, 5);
    $str2 = substr($message, 5);

    switch ($str) {

        case "%GPS ":
           // $str2 = str_replace(' ', '', $str2); // remove space after GPS. ("%GPS 32.333---")
            return array('gps', $str2);
            break;
        case "%BIN ":
            return array('bin', $str2);
            break;
        case "%JSON":
            return array('json', substr($message, 5));
            break;
        default:
            return array('txt', $message);
            break;
        }
    }

/**
 * return the max id of a message between two persons. 
 */
public static function getMaxId($sender, $receiver)
{
     $maxid = DB::select('select MAX(global_id) as max from messages where sender IN ("'.$sender.'", "'.$receiver.'") AND receiver IN ("'.$sender.'", "'.$receiver.'")')[0]->max;
     return $maxid;   
}

 /**
  * Return all messages between two persons. 
  */
public static function getAllMessages($sender, $receiver)
{
   $messages = DB::select('select global_id, sender, receiver, body, type from messages where 
    sender = "'.$sender.'" AND receiver = "'.$receiver.'" or 
    sender = "'.$receiver.'" AND receiver = "'.$sender.'" ORDER BY created_at asc');

   return $messages;
}


/*
* Used in the index view, to show who the user have a conversation with. 
*/
public static function getAllConversations($Authid)
{
    $conversations = DB::select('
SELECT sender as ss, receiver as rr, body, messages.type, created_at as date, global_id,
(SELECT id FROM users where global_id =  IF(sender = "'.$Authid.'", receiver, sender) limit 1) AS id
 FROM messages WHERE global_id IN(

SELECT distinct if( (SELECT max(`global_id`) FROM messages WHERE receiver = ss AND sender = rr) > mm, 
(SELECT max(`global_id`) FROM messages WHERE receiver = ss AND sender = rr), 
mm) 
AS test FROM

(SELECT sender AS ss, receiver as rr, max(global_id) as mm from messages WHERE sender = "'.$Authid.'" GROUP BY (receiver)
UNION
SELECT sender AS ss, receiver as rr, max(global_id) as mm from messages WHERE receiver = "'.$Authid.'" GROUP BY (sender) ) AS view 
)
');

    return $conversations;
}

//---------------------- Rest connections-----------------------------\\

public static function getGlobalAll()
{
   return  Rest::GET('http://caracal.imada.sdu.dk/app2019/messages');
}

public static function postMessage($sender, $receiver, $body)
{

        $data = '{
        "receiver": "'.$receiver.'",
        "body" : "'.$body.'", 
        "sender": "'.$sender.'"
        }';


 $respons = Rest::POST('http://caracal.imada.sdu.dk/app2019/messages', $data);

    if(gettype($respons) == 'integer' && $respons == 201)
    {
        return true;
    
    }else{

        return false; 
    }
}

/*
* Update the local database conversation with another user. 
*/
public static function putMessage($personOne, $personTwo, $id)
{   
    $id = ($id != null?'&id=gt.'.$id:"");

    $respons = Rest::GET('http://caracal.imada.sdu.dk/app2019/messages?sender=in.("'.$personOne.'","'.$personTwo.'")&receiver=in.("'.$personOne.'","'.$personTwo.'")'.$id.'');


    
    foreach( json_decode($respons) as $respon)
    {   
        
       $body = self::getType($respon->body); 
       $timestamp = TimeConverter::convert($respon->stamp); 

        $message = new Message();
        $message->global_id = $respon->id;
        $message->sender = $respon->sender; 
        $message->receiver = $respon->receiver; 
        $message->type = $body[0];
        $message->body = $body[1];
        $message->created_at = $timestamp;
        $message->updated_at = $timestamp;
        $message->save();   
    }
}


    public static function checkForNewMessages()
    {   
        $messageId = DB::select('Select global_id from messages ORDER BY created_at desc limit 1')[0]->global_id;
        $newMessages = json_decode(Rest::GET('http://caracal.imada.sdu.dk/app2019/messages?id=gt.'.$messageId.''));
        self::storeNewMessage($newMessages);
    }


/*
* the logic of insert a new user, 
* @parameter Std classs of users. 
*/
public static function storeNewMessage($newMessagges)
{
    foreach($newMessagges as $message)
    {   
        $timestamp = TimeConverter::convert($message->stamp);
        $type = self::getType($message->body); // return an array of array('type', 'message'), if the message is not a txt, it remove %BIN

        DB::table('messages')->insert(
        ['global_id' => $message->id, 'sender' => $message->sender, 'receiver' => $message->receiver, 'type' => $type[0], 'body' => $type[1], 'created_at' => $timestamp, 'updated_at' => $timestamp] );
    }
}













}
