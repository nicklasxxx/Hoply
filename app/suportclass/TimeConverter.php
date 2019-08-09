<?php

namespace App\suportclass;


// This class convert a PostGresql long stamp to a timestamp mysql can red. 

// postgresql: 2019-04-13T22:26:34.818001+02:00
// MySQL: 2019-04-13 22:26:34


Class TimeConverter
{

	public static function convert($time)
	{

	 $result = substr($time, 0, 19); // get the first 19 characters. 
	 $result = str_replace("T"," ",$result);
	 return $result;
	}





}