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
		Crew::loadData();
		$allCrewMembers = Crew::getAll();
		$crew=[];
		$flightDetails = "";
		
	foreach($allCrewMembers as $crewMember){
		$dataAA = $crewMember->getAssociativeArray();
		
		$assignedFlight = $crewMember->getAssignedFlight($dataAA['id']);
		if($assignedFlight == null){
			$alias = null;
			$flightNumber = "N/A";
			$airlineID = null;
			$flightID = null;
		}
		else{
			$alias = $assignedFlight['alias'];
			$flightNumber = $assignedFlight['alias']." ".$assignedFlight['airlineID'];
			$airlineID = $assignedFlight['airline'];
			$flightID = $assignedFlight['id'];
		}
		
		$countryDetails = $crewMember->getNationality($dataAA['nationality']);
		$nationality = $countryDetails['name'];
		$countryCode = $countryDetails['code'];
		$crew[] = ['id'=>$dataAA['id'], 'firstName'=>$dataAA['firstName'], 'lastName'=>$dataAA['lastName'], 'fullName'=>$dataAA['fullName'], 
			'gender'=>$dataAA['gender'], 'age'=>$dataAA['age'], 'nationality'=>$nationality, 'assignedFlight'=>$flightNumber, 
			'alias'=>$alias, 'country'=>$dataAA['nationality'], 'airlineID'=>$airlineID, 'flightID'=>$flightID, 'countryCode'=>$countryCode];
	}
	http_response_code(200);
	echo json_encode($crew);
	return;
	}
}	
http_response_code(401);
echo json_encode(['message'=>"Couldn't retrieve crew members summary"]);
?>