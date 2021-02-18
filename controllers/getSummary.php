<?php
require_once("../models/Flight.php");
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
	
		Flight::loadData();
		Crew::loadData();
		$allFlights = Flight::getAll();
		$flightCrews=[];	
		
	foreach($allFlights as $flight) {
		$crewMemberDetails = "";
		$flightAA = $flight->getAssociativeArray();
		$flightAA2 = $flight->getAirlineDetails($flightAA['airline']);

		$assignedCrew = $flight->getAssignedCrewMember($flightAA['id']);
		if($assignedCrew != null){
			foreach($assignedCrew as $crewMember) {
				$crewMemberDetails .= $crewMember['firstName']." ".$crewMember['lastName']." (".$crewMember['age'].")<br/>";
			}
		}
		else{
			$crewMemberDetails = "<br><br>N/A (No assigned crew members)";
		}
		$flightCrews[] = ['airlineDetails'=>$flightAA2['airlineName']." (".$flightAA2['alias']." ".$flightAA['airlineID'].") ",
							'alias'=>$flightAA2['alias'],
							'crewMemberDetails'=>$crewMemberDetails
		];
	}
	http_response_code(200);
	echo json_encode($flightCrews);
	return;
	}
}
http_response_code(401);
echo json_encode(['message'=>"Couldn't retrieve summary"]);
?>