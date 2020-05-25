<?php
Include_Once(__DIR__."/../api/app.php");

use EGroupware\Api;
use AgroEgw\DB;
use AgroEgw\Api\Timesheet;
use AgroEgw\Api\User;
use AgroEgw\Api\Timesheet\TimesheetSchema;
use ThreeCX\Manager as ThreeCXManager;

// Api\CalDAV::generate_uid('timesheet', $info_id);
$action = $_GET["action"];

if ($action == "newTimesheet") {
	$attr = $_REQUEST;

	$linked_addresses = explode(",", $attr["linked_addresses"]);

	if (!empty($linked_addresses[0])) {
		$hasLinkedAddresses = true;
	}

	$timesheet = new TimesheetSchema();
	$timesheet->ts_title = $attr["title"];
	$timesheet->ts_duration = floatval($attr["duration"]) * 60;
	$timesheet->ts_quantity = floatval($attr["duration"]);
	$timesheet->ts_unitprice = $attr["unitprice"];
	$timesheet->cat_id = $attr["category"];
	$timesheet->ts_start = strtotime($attr["startdate"]) ?: time();
	$timesheet->ts_modified = strtotime($attr["startdate"]) ?: time();
	$timesheet->ts_description = $attr["description"];
	$timesheet->ts_status = $attr["ts_status"] ?: 0;

	header('Content-Type: application/json');
	if (Timesheet::New($timesheet)){
		$LastInsertedId = Timesheet::LastInsertedId();
		if ($hasLinkedAddresses) {
			$links = Timesheet::Link("addressbook", $LastInsertedId, $linked_addresses);
		}
		ThreeCXManager::markIdAsInserted($attr["callid"]);
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
	echo json_encode(array());
}