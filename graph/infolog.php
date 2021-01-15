<?php
Include_Once(__DIR__."/../api/app.php");

use EGroupware\Api;
use AgroEgw\DB;
use AgroEgw\Api\Infolog;
use AgroEgw\Api\User;
use AgroEgw\Api\Infolog\InfologSchema;
use AgroEgw\Api\Infolog\InfologTracking;
use ThreeCX\Manager as ThreeCXManager;

// Api\CalDAV::generate_uid('infolog', $info_id);
$action = $_GET["action"];

if ($action == "newInfolog") {
	$attr = $_REQUEST;

	$linked_addresses = explode(",", $attr["linked_addresses"]);

	if (!empty($linked_addresses[0])) {
		$hasLinkedAddresses = true;
	}

	$infolog = new InfologSchema();
	$infolog->info_type = $attr["info_type"];
	$infolog->info_addr = $attr["tel_number"];
	$infolog->info_subject = $attr["info_title"];
	$infolog->info_des = $attr["info_des"];
	$infolog->info_owner = User::Me();
	$infolog->info_responsible = $attr["responsible_users"];
	$infolog->info_startdate = strtotime($attr["startdate"]) ?: time();
	$infolog->info_status = $attr["info_status"];
	$infolog->info_modifier = User::Me();
	$infolog->info_creator = User::Me();
	$infolog->info_created = time();

	header('Content-Type: application/json');
	if (Infolog::New($infolog)){
		$LastInsertedId = Infolog::LastInsertedId();
		if ($hasLinkedAddresses) {
			$links = Infolog::Link("addressbook", $LastInsertedId, $linked_addresses);
			(new DB("
				UPDATE `egw_infolog` 
				SET `info_link_id` = '".$links[0]."' WHERE `info_id` = $LastInsertedId;
			"));
		}
		ThreeCXManager::markIdAsInserted($attr["callid"]);
		$tracking = new InfologTracking();
		$tracking->track((array)Infolog::Get($LastInsertedId));
		echo json_encode(array(
			"response" => "success", 
			"last_id" => $LastInsertedId,
			"links" => $links
		));
	} else {
		echo json_encode(array("response" => "failure"));
	}
} else if ($action == "getStatuses") {
	header('Content-Type: application/json');
	echo json_encode(Infolog::InfoStatus());
}