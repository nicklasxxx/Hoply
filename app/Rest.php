<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
 
/*
* Make the connection the to server. 
*/    
private static function callAPI($method, $url, $data) {

   $curl = curl_init();

   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            // post fields is the Json data. 
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      case "DELETE":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                       
         break;


      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Authorization: Bearer ',
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

   // EXECUTE:
   $result = curl_exec($curl);
   $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

   if(!$result)
   {
     // die("Connection Failure");
      return $httpcode;
   }

   curl_close($curl);

   return $result;
  
   }

   public static function GET($url)
   {

    $url = str_replace(" ","%20",$url);  
   $get_data = self::callAPI('GET', $url, false);

   //header('Content-type: application/json');
   return json_encode( json_decode( $get_data ) );
  

   }

   /*
   *  Create a std_class object
   * or return a http code if the server return nothing. 
   */
   public static function POST($url, $data)
   {
      $get_data = self::callAPI('POST', $url, $data);
      return json_decode($get_data);
   }

   /**
    *  Create a std_class object
    * or return a http code if the server return nothing. 
    */
   public static function deleteGlobal($url, $data)
   {
      $get_data = self::callAPI('DELETE', $url, $data);
      return json_decode($get_data);
   }

 /**
    *  Create a std_class object
    * or return a http code if the server return nothing. 
    */
   public static function PUT($url, $data)
   {
      $get_data = self::callAPI('PUT', $url, $data);
      return json_decode($get_data);
   }



















}
