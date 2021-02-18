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
	$allFlights = Flight::getAll();
	$flights=[];
		
	foreach($allFlights as $flight){
		$dataAA = $flight->getAssociativeArray();
		$airlineAA = $flight->getAirlineDetails($dataAA['airline']);
		$countryDetails = $flight->getCountry($airlineAA['country']);
		$countryName = $countryDetails['name'];
		$countryCode = $countryDetails['code'];
		$flights[] = ['id'=>$dataAA['id'], 'airline'=>$dataAA['airline'], 'airlineName'=>$airlineAA['airlineName'], 
			'airlineID'=>$dataAA['airlineID'], 'alias'=>$airlineAA['alias'], 'callSign'=>$dataAA['callSign'], 'capacity'=>$dataAA['capacity'], 
			'country'=>$countryName, 'flightNumber'=>$dataAA['flightNumber'], 'crewCount'=>$dataAA['crewCount'], 'countryCode'=>$countryCode];
	}
	http_response_code(200);
	echo json_encode($flights);
	return;
	}
}
http_response_code(401);
echo json_encode(['message'=>"Couldn't retrieve flights summary"]);
?>