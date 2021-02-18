<?php
require_once("../models/Crew.php");
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
		if($_POST['currentView'] == "Flight"){
			Flight::loadData();
			$flights=[];
			$retrievedFlights = Flight::searchFlightSummary($_POST['searchTerm']);
			
			if($retrievedFlights == null){
				echo json_encode([]);
				return;
			}
			foreach($retrievedFlights as $flight){
				//if initialized as null instead of "", will return an undefined index error
				$matchingAirlineName = "";
				$matchingFlightNumber = "";
				$matchingCapacity = "";
				$matchingCountry = "";
				$matchingCallSign = "";
				$matchingCrewCount = "";
				
				//Initializes variables with the matching column values.
				if($_POST['searchTerm'] !== ""){
					if(strpos(strtolower($flight['airlineName']), strtolower($_POST['searchTerm'])) !== false){$matchingAirlineName = $flight['airlineName'];}
					if(strpos(strtolower($flight['flightNumber']), strtolower($_POST['searchTerm'])) !== false){$matchingFlightNumber = $flight['flightNumber'];}
					if(strpos(strtolower($flight['capacity']), strtolower($_POST['searchTerm'])) !== false){$matchingCapacity = $flight['capacity'];}
					if(strpos(strtolower($flight['name']), strtolower($_POST['searchTerm'])) !== false){$matchingCountry = $flight['name'];}
					if(strpos(strtolower($flight['callSign']), strtolower($_POST['searchTerm'])) !== false){$matchingCallSign = $flight['callSign'];}
					if(strpos(strtolower($flight['crewCount']), strtolower($_POST['searchTerm'])) !== false){$matchingCrewCount = $flight['crewCount'];}
				}
				
				$flights[] = ['id'=>$flight['id'], 'airline'=>$flight['airline'], 'airlineName'=>$flight['airlineName'], 'airlineID'=>$flight['airlineID'], 
				'alias'=>$flight['alias'], 'callSign'=>$flight['callSign'], 'capacity'=>$flight['capacity'], 'country'=>$flight['name'], 'flightNumber'=>$flight['flightNumber'], 
				'crewCount'=>$flight['crewCount'], 'matchingAirlineName'=>$matchingAirlineName, 'matchingFlightNumber'=>$matchingFlightNumber, 'matchingCapacity'=>$matchingCapacity, 
				'matchingCountry'=>$matchingCountry, 'matchingCallSign'=>$matchingCallSign, 'matchingCrewCount'=>$matchingCrewCount, 'countryCode'=>$flight['code']];
			}
			http_response_code(200);
			echo json_encode($flights);
			return;
		}
		else if($_POST['currentView'] == "Crew"){
			Crew::loadData();
			$crews=[];
			
			$_POST['searchTerm'] = strtolower($_POST['searchTerm']);
			$notApplicable = $_POST['searchTerm'] == "n/a" || $_POST['searchTerm'] == "n/" || $_POST['searchTerm'] == "/a" || $_POST['searchTerm'] == "/";
			if($notApplicable){
				$retrievedCrewMembers = Crew::searchCrewSummaryNull();
			}
			else{
				$retrievedCrewMembers = Crew::searchCrewSummary($_POST['searchTerm']);
			}
			if($retrievedCrewMembers == null){
				echo json_encode([]);
				return;
			}
			foreach($retrievedCrewMembers as $crewMembers){
				if($crewMembers['flightNumber'] == null){
					$assignedFlight = "N/A";
					$flightID = "null";
				}
				else{
					$assignedFlight = $crewMembers['flightNumber'];
					$flightID = $crewMembers['flightID'];
				}
				
				//if initialized as null instead of "", will return an undefined index error
				$matchingFullName = "";
				$matchingGender = "";
				$matchingAge = "";
				$matchingNationality = "";
				$matchingAssignedFlight = "";
				
				//Initializes variables with the matching column values.
				if($_POST['searchTerm'] !== ""){
					if(strpos(strtolower($crewMembers['fullName']), strtolower($_POST['searchTerm'])) !== false){$matchingFullName = $crewMembers['fullName'];}
					if(strpos(strtolower($crewMembers['gender']), strtolower($_POST['searchTerm'])) !== false){$matchingGender = $crewMembers['gender'];}
					if(strpos(strtolower($crewMembers['age']), strtolower($_POST['searchTerm'])) !== false){$matchingAge = $crewMembers['age'];}
					if(strpos(strtolower($crewMembers['name']), strtolower($_POST['searchTerm'])) !== false){$matchingNationality = $crewMembers['name'];}
					if(strpos(strtolower($assignedFlight), strtolower($_POST['searchTerm'])) !== false){$matchingAssignedFlight = $assignedFlight;}
				}
				$crews[] = ['id'=>$crewMembers['id'], 'firstName'=>$crewMembers['firstName'], 'lastName'=>$crewMembers['lastName'], 'fullName'=>$crewMembers['fullName'], 
					'gender'=>$crewMembers['gender'], 'age'=>$crewMembers['age'], 'flightID'=>$flightID, 'nationality'=>$crewMembers['name'], 'assignedFlight'=>$assignedFlight, 'countryCode'=>$crewMembers['code'],
					'alias'=>$crewMembers['alias'], 'country'=>$crewMembers['nationality'], 'airlineID'=>$crewMembers['airlineID'], 'matchingFullName'=>$matchingFullName, 
					'matchingGender'=>$matchingGender, 'matchingAge'=>$matchingAge, 'matchingNationality'=>$matchingNationality, 'matchingAssignedFlight'=>$matchingAssignedFlight
				];
			}
			http_response_code(200);
			echo json_encode($crews);
			return;
		}
	}
}
http_response_code(401);
echo json_encode(['message'=>"Couldn't retrieve search data"]);
return;
?>