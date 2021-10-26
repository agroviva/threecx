<?php
use EGroupware\Api\Header\ContentSecurityPolicy as CSP;
use AgroEgw\Api\Enqueue;

use ThreeCX\Manager as ThreeCXManager;
use ThreeCX\Request;

require_once dirname(__DIR__)."/api/app.php";

$_GET['cd'] = "no";

class threecx_ui
{
	var $public_functions = array(
		"init"		=> True,
		"synchron"	=> True
	);

	function __construct()
	{
		CSP::add_script_src(array('self', 'unsafe-eval', 'unsafe-inline'));
		$this->me = $GLOBALS['egw_info']["user"];

	}

	public function init() {
		Enqueue::Script("/egroupware/threecx/js/Settings.js");
		CSP::add_style_src(array("self","https://fonts.googleapis.com/"));
		CSP::add_style_src(array("self","https://maxcdn.icons8.com/"));
		
	
	}

	public function synchron(){
		// ThreeCXManager::$client = Request::$client;

		// $calllog = ThreeCXManager::$client->request("GET", Request::$URL."/api/CallLog?TimeZoneName=Europe%2FBerlin&callState=All&dateRangeType=Today&fromFilter=&fromFilterType=Any&numberOfRows=10&searchFilter=&startRow=0&toFilter=&toFilterType=Any");

		// $calllogObj = json_decode((string)$calllog->getBody());

		// ThreeCXManager::addCall($calllogObj);
	}

	/**
	* function create_header
	* this is creating the header for our non e-template approach
	*/
	public function create_header () {
		common::egw_header();
		echo parse_navbar();
	}

	/**
	* function create_footer
	* this is creating the footer for our non e-template approach
	*/
	public function create_footer () {
		common::egw_footer();
	}
}