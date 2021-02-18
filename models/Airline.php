<?php
require_once("Model.php");

class Airline extends Model{
	static private $airlines=[];
	private $airlineName;
	private $alias;	

	function __construct($id, $airlineName, $alias){
		$this->airlineName = $airlineName;
		$this->alias = $alias;
		Airline::$airlines[] = $this;
		Model::__construct();
	}

	static function loadData(): void{
		$data = [];

		try {
			$sql = "SELECT * FROM airline";
			$stmt = self::$pdo->prepare($sql);
			$executeSQL = $stmt->execute();
			while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$b = new Airline($obj['id'], $obj["airlineName"], $obj["alias"], $obj["country"]);
				$data[] = $b;
			}
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
	}

	function getAssociativeArray(): array {
		return ['id'=>$this->id, 'airlineName'=>$this->airlineName, 'alias'=>$this->alias];
	}
	
	static function getAll(): array {
		return Airline::$airlines;
	}
}
?>