<?php
require_once("../models/User.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (isset($_POST['tokenID'])) {
	if (User::checkTokenValidity($_POST['tokenID'])) {
		User::logout($_POST['tokenID']);
		http_response_code(200);
		echo json_encode(["message"=>"Successfully logged out"]);
		return;
	}
	else{
		http_response_code(403);
		echo json_encode(["message"=>"Unauthorized login. Logging out."]);
	}
}
http_response_code(401);
echo json_encode(['message'=>"Something went wrong"]);
?>