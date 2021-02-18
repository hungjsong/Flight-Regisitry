<?php
require_once("../models/Crew.php");
require_once("../models/User.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (isset($_POST['tokenID'])) {
	Model::initialize();
	if (User::checkTokenValidity($_POST['tokenID'])) {
		if (Crew::deleteCrewMember($_POST['id'], $_POST['currentFlightID'])) {
			http_response_code(200);
			echo json_encode([]);
		}
		else {
			http_response_code(403);
			echo json_encode(['message'=>"Something went wrong. Couldn't delete crew member."]);			
		}
		return;
	}	
}
http_response_code(401);
echo json_encode(['message'=>"Something went wrong. Couldn't delete crew member."]);
return;