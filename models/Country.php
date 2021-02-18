<?php
require_once("Model.php");

class Country extends Model{
	static private $countries=[];
	private $code;
	private $name;	

	function __construct($id, $code, $name){
		$this->code = $code;
		$this->name = $name;
		Country::$countries[] = $this;
		Model::__construct();
	}

	static function loadData(): void{
		$data = [];

		try {
			$sql = "SELECT * FROM country";
			$stmt = self::$pdo->prepare($sql);
			$executeSQL = $stmt->execute();
			while ($obj=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$b = new Country($obj['id'], $obj["code"], $obj["name"]);
				$data[] = $b;
			}
		}
		catch(Exception $e) {
			echo "EXCEPTION: ".$e->getMessage();
		}
	}

	function getAssociativeArray(): array {
		return ['id'=>$this->id, 'code'=>$this->code, 'name'=>$this->name];
	}
	
	static function getAll(): array {
		return Country::$countries;
	}
}
?>