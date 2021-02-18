<?php
require_once("../models/Model.php");
require_once("../models/User.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (isset($_POST['username']) && isset($_POST['password'])) {
	Model::initialize();
	$token = User::login($_POST['username'], $_POST['password']); 
	if (strlen($token)>0) {
		http_response_code(200);
		echo json_encode(['tokenID'=>$token]);
		return;
	}
	else{
		http_response_code(403);
		echo json_encode(['message'=>'Incorrect username or password.']);
		return;
	}
}
http_response_code(401);
echo json_encode(['message'=>'Something went wrong.']);
?>