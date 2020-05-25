<?php
Include_Once(__DIR__."/../api/app.php");

use EGroupware\Api;
use AgroEgw\DB;
use ThreeCX\Manager as ThreeCXManager;

$action = $_POST["action"];
$attr = $_POST;
$CallID = $attr['callid'];

header('Content-Type: application/json');
if ($action == "markID" && !empty($CallID)) {
	ThreeCXManager::markIdAsInserted($CallID);
	echo json_encode(array("response" => "success"));
} else {
	echo json_encode(array("response" => "failure"));
}