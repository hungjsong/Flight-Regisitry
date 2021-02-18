<?php
const DBUN = "id13928633_0201332asg2";
const DBPW = ">Password123";
const DBNM = "id13928633_admin";

abstract class Model {
	protected $id;
	static protected $pdo=null;
	static private $currentID=0;
	static private $objects=[];

	static function initialize() {
		if (is_null(self::$pdo))
			self::$pdo = new PDO("mysql:host=localhost; dbname=".DBNM, DBUN, DBPW);
	}

	function __construct() {
		Model::$currentID++;
		$this->id = Model::$currentID;
	}

	function getID() {
		return $this->id;
	}

	abstract static function getAll(): array;
	abstract static function loadData(): void;
}
?>