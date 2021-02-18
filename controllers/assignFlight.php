<?php
require_once("../models/Crew.php");
require_once("../models/User.php");
require_once("../models/Flight.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (isset($_POST['tokenID'])) {
	Model::initialize();
	
	//Allows for user to reassign crew member to same flight without incurring an error
	if (User::checkTokenValidity($_POST['tokenID'])) {
		if($_POST['newFlightID'] == $_POST['currentFlightID']){
			http_response_code(200);
			echo json_encode([]);
			return;
		}
		
		else if($_POST['newFlightID'] === 'null'){
				$_POST['newFlightID'] = null;
				if (Crew::assignFlight($_POST['crewMemberID'], $_POST['newFlightID'], $_POST['currentFlightID'])) {
				http_response_code(200);
				echo json_encode([]);
				return;
			}
		}
		else if($_POST['currentFlightID'] === 'null'){
				$_POST['currentFlightID'] = null;
				if (Crew::assignFlight($_POST['crewMemberID'], $_POST['newFlightID'], $_POST['currentFlightID'])) {
				http_response_code(200);
				echo json_encode([]);
				return;
			}
		}
		
		//if $_POST['newFlightID'] = null, then retrieveCrewCount will fail.
		else if(Crew::retrieveCrewCount($_POST['newFlightID']) < 5){
			if (Crew::assignFlight($_POST['crewMemberID'], $_POST['newFlightID'], $_POST['currentFlightID'])) {
				http_response_code(200);
				echo json_encode([]);
				return;
			}
		}
		else{
			http_response_code(403);
			echo json_encode(['message'=>"Update failed. Selected flight is currently full."]);
			return;
		}
	}	
}
http_response_code(401);
echo json_encode(['message'=>"Couldn't assign to flight."]);
return;