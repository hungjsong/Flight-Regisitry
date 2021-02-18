<?php
require_once("../models/Airline.php");
require_once("../models/User.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

	
if (isset($_POST['tokenID'])) {
	Model::initialize();
	if (User::checkTokenValidity($_POST['tokenID'])) {
	
	Airline::loadData();
	$allAirlines = Airline::getAll();
	$airlines=[];
		
	foreach($allAirlines as $airline){
		$dataAA = $airline->getAssociativeArray();
		$airlines[] = ['id'=>$dataAA['id'],'airlineName'=>$dataAA['airlineName'], 'alias'=>$dataAA['alias']];
	}
	http_response_code(200);
	echo json_encode($airlines);
	return;
	}
}
http_response_code(401);
echo json_encode(['message'=>"Couldn't retrieve airlines."]);
?>