<?php
require_once("../models/Flight.php");
require_once("../models/User.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (isset($_POST['tokenID'])) {
	Model::initialize();
	if (User::checkTokenValidity($_POST['tokenID'])) {
		if (Flight::addFlight($_POST['airline'], $_POST['airlineID'],  $_POST['capacity'])) {
			http_response_code(200);
			echo json_encode([]);
		}
		else {
			http_response_code(403);		
			echo json_encode(['message'=>"Add flight failed. Entered flight details currently in use by another flight."]);		
		}
		return;
	}	
}
http_response_code(401);
echo json_encode(['message'=>"Couldn't add flight."]);
return;