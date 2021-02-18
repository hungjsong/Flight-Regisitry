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
		Flight::loadData();
		$allFlights = Flight::getNonFullFlights();
		$flights=[];
			
		foreach($allFlights as $flight){
			$flights[] = ['id'=>$flight['id'], 'airlineName'=>$flight['airlineName'], 'airlineID'=>$flight['airlineID'], 'alias'=>$flight['alias']];
		}
		http_response_code(200);
		echo json_encode($flights);
		return;
	}
}
http_response_code(401);
echo json_encode(['message'=>"Couldn't retrieve non-full flights"]);
?>