<?php
require_once("Model.php");

Class Crew extends Model{
	protected $id;
	private $firstName;
	private $lastName;
	private $fullName;
	private $age;
	private $gender;
	private $nationality;
	static private $crewMembers = [];
	
	function __construct($id, $firstName, $lastName, $fullName, $age, $gender, $nationality) {
		$this->id = $id;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->fullName = $fullName;
		$this->age = $age;
		$this->gender = $gender;
		$this->nationality = $nationality;
		Crew::$crewMembers[] = $this;
	}

	static function loadData(): void{
		self::$crewMembers=[];
		//All the innerjoins are to enable ordering by airline name and to include crew members not assigned to a flight.
		try {
			$sql = "SELECT crew.id, crew.firstName, crew.lastName, crew.fullName, crew.age, crew.gender, crew.nationality
					FROM crew 
                    LEFT OUTER JOIN (flight_crew INNER JOIN (flight INNER JOIN airline ON flight.airline = airline.id) ON flight_crew.flightID = flight.id)
					ON crew.id=flight_crew.crewID
					ORDER BY airline.airlineName";
			$stmt = self::$pdo->prepare($sql);
			$executeSQL = $stmt->execute();
			while ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$crew = new Crew($obj['id'], $obj["firstName"], $obj["lastName"], $obj["fullName"], $obj["age"], $obj["gender"], $obj["nationality"]);
			}
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
	}
		
	static function getSpecificCrewMember($crewID): array{
		try {
			$sql = "SELECT firstName, lastName, age
					FROM `crew`
					WHERE `id` = :crewID";
			$stmt = self::$pdo->prepare($sql);
			$executeSQL = $stmt->execute([':crewID'=>$crewID]);
			while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
				return $obj;
			}
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
	}
	
	static function getAssignedFlight($crewID){
		try {
			$sql = "SELECT airline.alias, flight.id, flight.airline, flight.airlineID
					FROM airline JOIN
					(flight JOIN flight_crew
					ON flight_crew.flightID=flight.id)
					ON airline.id=flight.airline
					WHERE flight_crew.crewID = :crewID";
			$stmt = self::$pdo->prepare($sql);
			$executeSQL = $stmt->execute([':crewID'=>$crewID]);
			while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
				return $obj;
			}
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
	}
	
	static function getAll(): array{
		return Crew::$crewMembers;
	}

	function getAssociativeArray(): array {
		return ['id'=>$this->id, 
				'firstName'=>$this->firstName, 
				'lastName'=>$this->lastName, 
				'fullName'=>$this->fullName, 
				'gender'=>$this->gender, 
				'age'=>$this->age, 
				'nationality'=>$this->nationality];
	}
	
	function getNationality($countryID){
		try {
			$sql = "SELECT name, code
					FROM country
					WHERE id = :countryID";
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
	
	static function addCrewMember($firstName, $lastName, $gender, $age, $nationality): bool {
		if (strlen($firstName)>0) {
			try {
				$sql = "INSERT INTO crew (firstName, lastName, fullName, gender, age, nationality) 
						VALUES (:firstName, :lastName, :fullName, :gender, :age, :nationality)";
				$stmt= self::$pdo->prepare($sql);
				$affected = $stmt->execute([':firstName'=>$firstName, 
											':lastName'=>$lastName, 
											':fullName'=>$firstName.' '.$lastName, 
											':gender'=>$gender, 
											':age'=>$age, 
											':nationality'=>$nationality]);
				$sql = "INSERT INTO flight_crew (crewID) 
						VALUES (LAST_INSERT_ID())";
				$stmt = self::$pdo->prepare($sql);
				$affected = $stmt->execute();
				return ($affected==1);
			}
			catch(Exception $e) {
				echo "EXCEPTION: ".$e->getMessage();				
			}
		}
		return false;
	}
	
	static function updateMemberDetails($id, $firstName, $lastName, $gender, $age, $country): bool {
		if (strlen($firstName) > 0) {
			try {
				$sql = "UPDATE `crew`
						SET firstName=:firstName,
						lastName=:lastName,
						fullName=:fullName,
						gender=:gender,
						age=:age,
						nationality=:country
						WHERE id=:id";
				$stmt = self::$pdo->prepare($sql);
				$affected = $stmt->execute([':firstName'=>$firstName,
											':lastName'=>$lastName,
											':fullName'=>$firstName.' '.$lastName,
											':gender'=>$gender,
											':age'=>$age,
											':country'=>$country,
											':id'=>$id]);
				return ($affected == 1);
			}
			catch(Exception $e) {
				echo "EXCEPTION: ".$e->getMessage();				
			}
		}
		return false;
	}
	
	function assignFlight($crewID, $flightID, $previousFlightID): bool{
		if ($crewID > 0) {
			try {
				$sql = "UPDATE `flight_crew`
						SET flightID=:flightID
						WHERE crewID=:crewID";
				$stmt = self::$pdo->prepare($sql);
				$affected = $stmt->execute([':flightID'=>$flightID,
											':crewID'=>$crewID]);
				
				//Removing from flight
				if($flightID == null){
					$sql = "UPDATE `flight`
							SET crewCount=:crewCount
							WHERE id=:flightID";
					$stmt = self::$pdo->prepare($sql);
					$crewCount = (Crew::retrieveCrewCount($previousFlightID));
					$affected = $stmt->execute([':flightID'=>$previousFlightID,
												':crewCount'=>$crewCount]);
				}
				//Crew Member previously had no flight
				else if($previousFlightID == null){
					$sql = "UPDATE `flight`
							SET crewCount=:crewCount
							WHERE id=:flightID";
					$stmt = self::$pdo->prepare($sql);
					$crewCount = (Crew::retrieveCrewCount($flightID));
					$affected = $stmt->execute([':flightID'=>$flightID,
												':crewCount'=>$crewCount]);
				}
				//Crew Member transfered to another flight
				else{
					//New flight crew count++
					$sql = "UPDATE `flight`
							SET crewCount=:crewCount
							WHERE id=:flightID";
					$stmt = self::$pdo->prepare($sql);
					$crewCount = (Crew::retrieveCrewCount($flightID));
					$affected = $stmt->execute([':flightID'=>$flightID,
												':crewCount'=>$crewCount]);
					
					//Previous flight crew count--
					$sql = "UPDATE `flight`
							SET crewCount=:crewCount
							WHERE id=:flightID";
					$stmt = self::$pdo->prepare($sql);
					$crewCount = (Crew::retrieveCrewCount($previousFlightID));
					$affected = $stmt->execute([':flightID'=>$previousFlightID,
												':crewCount'=>$crewCount]);
				}
				return ($affected == 1);
			}
			catch(Exception $e) {
				echo "EXCEPTION: ".$e->getMessage();				
			}
		}
		return false;
	}
	
	function deleteCrewMember($crewID, $currentFlightID):bool{
		if ($crewID > 0) {
			try {
				$sql = "DELETE FROM flight_crew 
						WHERE crewID = :crewID";
				$stmt = self::$pdo->prepare($sql);
				$affected = $stmt->execute([':crewID'=>$crewID]);
				
				$sql = "DELETE FROM crew
						WHERE id = :crewID";
				$stmt = self::$pdo->prepare($sql);
				$affected = $stmt->execute([':crewID'=>$crewID]);
				
				if($currentFlightID != null){
				$sql = "UPDATE `flight`
						SET crewCount=:crewCount
						WHERE id=:flightID";
					$stmt = self::$pdo->prepare($sql);
					$crewCount = (Crew::retrieveCrewCount($currentFlightID));
					$affected = $stmt->execute([':flightID'=>$currentFlightID,
												':crewCount'=>$crewCount
												]);						
				}
				return ($affected == 1);
			}
			catch(Exception $e) {
				echo "EXCEPTION: ".$e->getMessage();				
			}
		}
		return false;	
	}
	
	static function retrieveCrewCount($flightID): int{
			try{	
				$counter = 0;
				$sql = "SELECT *
						FROM `flight_crew`
						WHERE `flightID` = :flightID";
				$stmt = self::$pdo->prepare($sql);
				$executeSQL = $stmt->execute([':flightID'=>$flightID]);
				while ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$counter++;
				}
				return $counter;
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
	}
	
	static function searchCrewSummary($searchTerm){
		try{
			$retrievedCrewMembers=[];
			$sql = "SELECT crew.fullName, crew.age, crew.gender, nationality.name, flight.flightNumber, crew.firstName, 
						crew.lastName, crew.id, crew.nationality, flight.airlineID, airline.alias, airline.airlineName, nationality.code, flight_crew.flightID
					FROM country nationality INNER JOIN (crew LEFT OUTER JOIN (flight_crew INNER JOIN (flight INNER JOIN airline 
					ON flight.airline = airline.id)
					ON flight_crew.flightID = flight.id) 
					ON crew.id = flight_crew.crewID)
					ON nationality.id = crew.nationality
					WHERE crew.fullName LIKE :searchTerm
					OR crew.age LIKE :searchTerm
					OR crew.gender LIKE :searchTerm
					OR nationality.name LIKE :searchTerm
					OR flight.flightNumber LIKE :searchTerm
					ORDER BY airline.airlineName";
			$stmt= self::$pdo->prepare($sql);
			$executeSQL = $stmt->execute([':searchTerm'=>'%'.$searchTerm.'%']);
			while ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$retrievedCrewMembers[] = $obj;
			}
			return $retrievedCrewMembers;
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();				
		}
	}
	
	static function searchCrewSummaryNull(){
		try{
			$retrievedCrewMembers=[];
			$sql = "SELECT crew.fullName, crew.age, crew.gender, nationality.name, flight.flightNumber, crew.firstName, crew.lastName, 
						crew.id, crew.nationality, flight.airlineID, airline.alias, nationality.code, flight_crew.flightID
					FROM country nationality INNER JOIN (crew LEFT OUTER JOIN (flight_crew INNER JOIN (flight INNER JOIN airline 
					ON flight.airline = airline.id)
					ON flight_crew.flightID = flight.id) 
					ON crew.id = flight_crew.crewID)
					ON nationality.id = crew.nationality
					WHERE flight_crew.flightID IS NULL";
			$stmt= self::$pdo->prepare($sql);
			$executeSQL = $stmt->execute();
			while ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$retrievedCrewMembers[] = $obj;
			}
			return $retrievedCrewMembers;
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();				
		}
	}
}
?>