<?php
require_once("Model.php");
require_once("Crew.php");

class Flight extends Model{
	protected $id;
	static private $flights = [];
	private $airline;
	private $airlineID;
	private $seatCapacity;
	private $callSign;
	private $flightNumber;
	private $crewCount;

	function __construct($id, $airline, $airlineID, $seatCapacity, $callSign, $flightNumber, $crewCount) {
		$this->id = $id;
		$this->airline = $airline;
		$this->airlineID = $airlineID;
		$this->seatCapacity = $seatCapacity;
		$this->callSign = $callSign;
		$this->flightNumber = $flightNumber;
		$this->crewCount = $crewCount;
		Flight::$flights[] = $this;
	}

	static function loadData(): void{
		self::$flights=[];
		try {
			$sql = "SELECT flight.id, flight.airline, flight.airlineID, flight.capacity, flight.callSign, airline.airlineName, flight.flightNumber, flight.crewCount
					FROM flight
					INNER JOIN airline
					ON flight.airline = airline.id
					ORDER BY airline.airlineName";
			$stmt = self::$pdo->prepare($sql);
			$executeSQL = $stmt->execute();
			while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$flight = new Flight($obj['id'], $obj["airline"], $obj["airlineID"], $obj["capacity"], $obj["callSign"], $obj["flightNumber"], $obj["crewCount"]);
			}
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
	}

	static function addFlight($airline, $airlineID, $capacity): bool {
		if ($airline>0) {
			try {
				$sql = "SELECT *
						FROM airline
						WHERE id = :airline";
				$stmt = self::$pdo->prepare($sql);
				$executeSQL = $stmt->execute([':airline'=>$airline]);
				$obj = $stmt->fetch(PDO::FETCH_ASSOC);
				
				$prunedValues = Flight::pruneZeroes($airlineID);
				
				$convertedAirlineID = implode(" ", $prunedValues['newAirlineID']);
				$callSign = $obj['callSignPrefix']." ".$convertedAirlineID;
				$flightNumber = $obj['alias']." ".$prunedValues['prunedString'];
				
				$sql = "INSERT INTO flight (airline, airlineID, callSign, capacity, flightNumber) 
						VALUES (:airline, :airlineID, :callSign, :capacity, :flightNumber)";
				$stmt = self::$pdo->prepare($sql);
				$affected = $stmt->execute([':airline'=>$airline, 
											':airlineID'=>$airlineID, 
											':callSign'=>$callSign, 
											':capacity'=>$capacity, 
											':flightNumber'=>$flightNumber]);
				return ($affected == 1);
			}
			catch(Exception $e) {
				echo "EXCEPTION: ".$e->getMessage();				
			}
		}
		return false;
	}

	static function updateFlightDetails($id, $airline, $airlineID, $capacity): bool {
		if ($id > 0) {
			try {
				$sql = "SELECT *
						FROM airline
						WHERE id = :airline";
				$stmt = self::$pdo->prepare($sql);
				$executeSQL = $stmt->execute([':airline'=>$airline]);
				$obj=$stmt->fetch(PDO::FETCH_ASSOC);
				
				$prunedValues = Flight::pruneZeroes($airlineID);
				
				$convertedAirlineID = implode(" ", $prunedValues['newAirlineID']);
				$callSign = $obj['callSignPrefix']." ".$convertedAirlineID;
				$flightNumber = $obj['alias']." ".$prunedValues['prunedString'];
				
				$sql = "UPDATE `flight`
						SET airline=:airline,
						airlineID=:airlineID,
						callSign=:callSign,
						capacity=:capacity,
						flightNumber=:flightNumber
						WHERE id=:id";
				$stmt = self::$pdo->prepare($sql);
				$affected = $stmt->execute([':airline'=>$airline,
											':airlineID'=>$airlineID,
											':callSign'=>$callSign,
											':capacity'=>$capacity,
											':flightNumber'=>$flightNumber,
											':id'=>$id
										]);
				return ($affected == 1);
			}
			catch(Exception $e) {
				echo "EXCEPTION: ".$e->getMessage();				
			}
		}
		return false;
	}
	
	function pruneZeroes($airlineID){
		$prunedZeroes = false;
		$prunedAirlineID = [];
		$array = str_split($airlineID);
		
		foreach ($array as $char) {
			if($prunedZeroes == false){
				if($char != "0"){
					$prunedZeroes = true;
					$prunedAirlineID[] = $char;
				}
			}	
			else{
				switch ($char) {
					case "0": $prunedAirlineID[] = "0"; break;
					case "1": $prunedAirlineID[] = "1"; break;
					case "2": $prunedAirlineID[] = "2"; break;
					case "3": $prunedAirlineID[] = "3"; break;
					case "4": $prunedAirlineID[] = "4"; break;
					case "5": $prunedAirlineID[] = "5"; break;
					case "6": $prunedAirlineID[] = "6"; break;
					case "7": $prunedAirlineID[] = "7"; break;
					case "8": $prunedAirlineID[] = "8"; break;
					case "9": $prunedAirlineID[] = "9"; break;
					default: break;
				}
			}
		}
		$prunedString = implode("", $prunedAirlineID);
		$prunedArray = str_split($prunedString);
		
		$newAirlineID = [];
		foreach ($prunedArray as $prunedChar){		
				switch ($prunedChar) {
					case "0": $newAirlineID[] = "Zero"; break;
					case "1": $newAirlineID[] = "One"; break;
					case "2": $newAirlineID[] = "Two"; break;
					case "3": $newAirlineID[] = "Three"; break;
					case "4": $newAirlineID[] = "Four"; break;
					case "5": $newAirlineID[] = "Five"; break;
					case "6": $newAirlineID[] = "Six"; break;
					case "7": $newAirlineID[] = "Seven"; break;
					case "8": $newAirlineID[] = "Eight"; break;
					case "9": $newAirlineID[] = "Niner"; break;
					default: break;
			}
		}
		return ['newAirlineID'=>$newAirlineID, 'prunedString'=>$prunedString];
	}

	function getAssignedCrewMember($flightID): array{
		$assignedCrew = [];
		try {
			$sql = "SELECT `crewID` 
			FROM flight_crew
			WHERE flightID = :flightID";
			$stmt= self::$pdo->prepare($sql);
			$executeSQL = $stmt->execute([':flightID'=>$flightID]);
			
			while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$findMatchingCrewMember = Crew::getSpecificCrewMember($obj["crewID"]);
				$matchingCrewMemberFound = !is_null($findMatchingCrewMember);
				if ($matchingCrewMemberFound){
					$assignedCrew[] = $findMatchingCrewMember;
				}
				else{
					Die("failed to find crew member with id "+ $flightID);
				}
			}
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
		return $assignedCrew;
	}
	
	static function getAll(): array {
		return Flight::$flights;
	}
	
	static function getNonFullFlights(): array{
		try{
			$retrievedFlights=[];
				$sql = "SELECT flight.id, airline.airlineName, airline.alias, flight.airlineID
						FROM flight INNER JOIN airline ON flight.airline = airline.id
						WHERE crewCount < 5";
				$stmt = self::$pdo->prepare($sql);
				$executeSQL = $stmt->execute();
				while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
					$retrievedFlights[] = $obj;
				}
				return $retrievedFlights;
			}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();				
		}
	}
	
	function getAssociativeArray(): array {
		return ['id'=>$this->id, 
				'airline'=>$this->airline, 
				'airlineID'=>$this->airlineID, 
				'callSign'=>$this->callSign, 
				'capacity'=>$this->seatCapacity, 
				'flightNumber'=>$this->flightNumber,
				'crewCount'=>$this->crewCount];
	}
	
	function getAirlineDetails($airlineID){
		try {
				$sql = "SELECT *
						FROM `airline`
						INNER JOIN `flight`
						ON flight.airline = airline.id
						WHERE flight.airline = :airlineID";
				$stmt = self::$pdo->prepare($sql);
				$executeSQL = $stmt->execute([':airlineID'=>$airlineID]);
				while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
					return $obj;
				}
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
	}
	
	function getCountry($countryID){
		try {
				$sql = "SELECT name, code
						FROM `country`
						WHERE `id` = :countryID";
				$stmt = self::$pdo->prepare($sql);
				$executeSQL = $stmt->execute([':countryID'=>$countryID]);
				while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
					return $obj;
				}
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
	}
	
	function deleteFlight($flightID):bool{
		if ($flightID > 0) {
			try {	
				$sql = "UPDATE flight_crew 
						SET flightID = null
						WHERE flightID = :flightID";
				$stmt = self::$pdo->prepare($sql);
				$affected = $stmt->execute([':flightID'=>$flightID]);
			
				$sql = "DELETE FROM flight
						WHERE id = :flightID";
				$stmt = self::$pdo->prepare($sql);
				$affected = $stmt->execute([':flightID'=>$flightID]);
				return ($affected == 1);
			}
			catch(Exception $e) {
				echo "EXCEPTION: ".$e->getMessage();				
			}
		}
		return false;	
	}
	
	static function searchFlightSummary($searchTerm){
		try{
			$retrievedFlights=[];
				$sql = "SELECT airline.airlineName, airline.alias, country.name, flight.callSign, flight.capacity, flight.flightNumber, flight.airlineID, flight.id, 
							flight.airline, flight.crewCount, country.code
						FROM flight INNER JOIN (airline INNER JOIN country
						ON airline.country = country.id)
						ON flight.airline = airline.id
						WHERE flight.flightNumber LIKE :searchTerm
						OR flight.capacity LIKE :searchTerm
						OR flight.callSign LIKE :searchTerm
						OR airline.airlineName LIKE :searchTerm
						OR country.name LIKE :searchTerm
						OR flight.crewCount LIKE :searchTerm
						ORDER BY airline.airlineName";
				$stmt = self::$pdo->prepare($sql);
				$executeSQL = $stmt->execute([':searchTerm'=>'%'.$searchTerm.'%']);
				while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
					$retrievedFlights[] = $obj;
				}
				return $retrievedFlights;
			}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();				
		}
	}
}
?>