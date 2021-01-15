<?php
namespace ThreeCX;

use AgroEgw\DB;

class Manager
{
    static $client;

    static $booklist;

    static function getList(){
    	$name = $GLOBALS["egw_info"]["user"]["account_firstname"];
    	// return DB::GetAll("SELECT * FROM egw_threecx_calllist 
    	// 	WHERE CallerId LIKE '%$name%' OR Destination LIKE '%$name%'
    	// 	ORDER BY CallTime DESC LIMIT 50
    	// ");

    	return DB::GetAll("SELECT * FROM egw_threecx_calllist
    		ORDER BY CallTime DESC LIMIT 100
    	");
    }

    static function addCall($object){
    	foreach ($object->CallLogRows as $key => $call) {
			$CallID = self::parseID($call);

			if (self::callExists($CallID)) {
				continue;
			} else {
				$CallTime = strtotime($call->CallTime);
				$CallerId = addslashes($call->CallerId);
				$Destination = addslashes($call->Destination);
				$Duration = self::seconds_from_time($call->Duration) ?? 0;
				$Answered = $call->Answered ? 1 : 0;

				// Dump($Duration);

				DB::Run("
					INSERT INTO egw_threecx_calllist
					VALUES ('$CallID', '$CallTime', '$CallerId', '$Destination', '$Duration', '$Answered')
				");
			}
		}
    }

    static function callExists($CallID){
    	$data = DB::Get("SELECT * FROM egw_threecx_calllist WHERE CallID = '$CallID'");

    	return empty($data) ? false : true;
    }

    static function seconds_from_time($time) {
		list($h, $m, $s) = explode(':', $time);
		return ($h * 3600) + ($m * 60) + $s;
	}
	static function time_from_seconds($seconds) {
		$h = floor($seconds / 3600);
		$m = floor(($seconds % 3600) / 60);
		$s = $seconds - ($h * 3600) - ($m * 60);
		return sprintf('%02d:%02d:%02d', $h, $m, $s);
	}

    static function getBookList(){
    	if (self::$booklist) {
    		return self::$booklist;
    	}
		$booklist = self::$client->request("GET", Request::$URL."/api/PhoneBookEntryList");
		$booklist = json_decode((string)$booklist->getBody());
		self::$booklist = $booklist->list;
		return $booklist->list;
	}

	static function parseNumber($string){
		preg_match('#\((.*?)\)#', $string, $match);
		$number = $match[1] ?? $string;

		$number = preg_replace('/[^0-9]/', '', $number);
		return $number;
	}

	static function searchForNumber($number, $booklist) {
		if(empty($number)){return null;}
		foreach ($booklist as $key => $val) {
		   if ($val->MobileNumber === $number || $val->Home === $number || $val->Business === $number) {
		       return $val;
		   } 
		}
	   return null;
	}

	static function findUserByNumber($number){
		$number = self::parseNumber($number);
		$booklist = self::getBookList();

		return self::searchForNumber($number, $booklist);
	}

	static function getTitle($number){
		$user = self::findUserByNumber($number);
		if (empty($user)) {
			return $number;
		}
		return $user->Company ?: trim($user->FirstName." ".$user->LastName);
	}

	static function parseID($call){
		$id = $call->CallTime.$call->CallerId;
		$id = preg_replace('/[^0-9]/', '', $id);
		return $id;
	}

	static function markIdAsInserted($CallID){
		if (self::isIDMarked($CallID)) {return;}
		(new DB("
			INSERT INTO egw_threecx_meta 
			(meta_name, meta_connection_id, meta_data) 
			VALUES('marked', 0, '$CallID')
		"));
	}

	static function isIDMarked($CallID){
		$result = (new DB("
			SELECT * FROM egw_threecx_meta WHERE meta_data = '$CallID';
		"))->Fetch();
		if (!empty($result)) {
			return true;
		}
		return false;
	}

	static function isCallMarked($CallID){
		return self::isIDMarked($CallID);
	}
}
