<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\suportclass\Setup;
use App\Message;
use App\Follow;
use App\User;
use Auth;

class MessageController extends Controller
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
    * return a view of all message convensations. 
    */
    public function index()
    {   
        $messages = Message::getAllConversations(Auth::user()->global_id);

        return view('Message.index', compact('messages'));
    }
  

    /**
    *  return a conversation view with the Auth and a user.
    */
    public function show(int $id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $sender = Auth::user()->global_id;
        $receiver = $user->global_id;
        //$maxid = Message::getMaxId($sender, $receiver); 
        //Message::putMessage($receiver, $sender, $maxid); // update local database before if there is any now message.
        $messages = Message::getAllMessages($sender, $receiver);

        return view('Message.show', compact('user', 'messages') );

    }

    /*
    * By ajaxt post, send a message to server. 
    */
    public function postMessage(Request $request)
    {   
        $validatedData = $request->validate([
        'receiver' => 'required|string',
        'body' => 'required|string',
        'type' => ['required', 'string', Rule::in(['txt', 'gps'])]
        ]);
    

        $receiver = User::where('id', $request->receiver)->firstOrFail();
        $maxid = Message::getMaxId(Auth::user()->global_id, $receiver->global_id); 
        $sender = Auth::user()->global_id; 
        $body = ($request->type == 'gps'?"%GPS ".$request->body:$request->body);

        if(Message::postMessage($sender, $receiver->global_id, $body) )   
        {   
             Message::putMessage($receiver->global_id, Auth::user()->global_id, $maxid);

             return json_encode(array("status" => "success"), JSON_PRETTY_PRINT);
            
        }else{
             
            return json_encode(array("status" => "fail"), JSON_PRETTY_PRINT);
        }

    }

    /**
     * methods for posting an image.
     */
    public function postImage(Request $request)
    {
          $validatedData = $request->validate([
                'user' => 'required|string',
                'image' => 'file|mimes:jpeg,gif,png,jpg'
        ]);

        if(isset($_FILES['image']))
        {   
            $allowed_ext= array('jpg','jpeg','png','gif');
            $file_tmp= $_FILES['image']['tmp_name'];            
            $type = $_FILES['image']['type'];
            $data = file_get_contents($file_tmp);
            $body = '%BIN data:'.$type.';base64,' . base64_encode($data);
            $sender = Auth::user()->global_id;
            $receiver = User::where('id', $request->user)->firstOrFail();
           
               if(Message::postMessage($sender, $receiver->global_id, $body) )   
                {   
                     Message::checkForNewMessages();
                }
        }

        return redirect()->back();

    }

    /*
     * post method, check for new messages, and update the local database. 
     */
    public function postUpdate()
    {
        Message::checkForNewMessages(); // check for new users, before showing page. 
        return redirect()->back();
    }












}
