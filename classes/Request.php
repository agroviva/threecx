<?php
namespace ThreeCX;

use GuzzleHttp\Client as Client;
use GuzzleHttp\Cookie\FileCookieJar as FileCookieJar;
use GuzzleHttp\Cookie\CookieJar as CookieJar;
use AgroEgw\DB;

class Request
{

    static $URL = "";

	static $login_array = array(
		"Username" => "",
		"Password" => ""
	);

	static $client;

	static function Init($token){
		// file to store cookie data
		$cookieFile = __DIR__."/../graph/cookies/$token.txt";

		$cookieJar = new FileCookieJar($cookieFile, TRUE);

		static::$client = new Client([
			'http_errors' => false,
			'cookies' => $cookieJar
		]);

		$s = self::$client->request('GET', self::$URL);
	}

	static function Login(){
		$r = self::$client->request('POST', self::$URL."/api/login", 
			[
				'json' => self::$login_array
			]
		);
		return $r;
	}

	static function isLoggedIn(){
		if(is_null(self::$client)){return false;}

		$s = self::$client->request('GET', self::$URL."/api/CurrentUser");
		$result = (string)$s->getBody();
		if (!empty(json_decode($result, true))) {
			return true;
		} else {
			$result = (string)self::Login()->getBody();
			return $result == "AuthSuccess";
		}
	}

	static function init_static(){
		$Data = (new DB("SELECT * FROM egw_threecx WHERE id = 1"))->Fetch();
		if (!empty($Data)) {
			$login = json_decode($Data["data"], true);
			self::$URL = trim($login["url"], "/");
			self::Init($login["token"]);
			self::$login_array["Username"] = $login["username"];
			self::$login_array["Password"] = $login["password"];
		} 
	}	
}
Request::init_static();
