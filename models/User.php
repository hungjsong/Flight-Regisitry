<?php
//pass123 is the hashed password for username: admin

class User {
	static function login($username, $password): string {
		$accountCredentials = fopen("../models/users.json","r");
		while (!feof($accountCredentials)){
			$nextLine = fgets($accountCredentials);
			$accountObject = json_decode($nextLine, true);
			if($username == $accountObject["username"] && self::hashPassword($password) == $accountObject["hashedPassword"]){
				$tokenID=hash("SHA512","a8sdfy823".session_id()."adiuyfo13z");
				self::setCurrentSession($accountObject['id'], session_id(), $tokenID);
				return $tokenID;
			}
			else{
				return "";
			}
		}
	}
	
	static function hashPassword($password): string {
		return hash("SHA512","27aysd2k2z".$password."ahs98dy233",false);
	}
	
	static function setCurrentSession($userID, $sessionID, $tokenID){
		$contentsFromFile = fopen("../models/sessionDetails.json","r");
		$nextLine = fgets($contentsFromFile);
		$sessionDetails = json_decode($nextLine, true);
		$foundSession = false;
		foreach($sessionDetails as $session){
			if ($session['userID'] == $userID){
				$session['sessionID'] = $sessionID;
				$session['tokenID'] = $tokenID;
				$foundSession = true;
				break;
			}
		}
		$noSessionsFound = !$foundSession;
		if ($noSessionsFound) {
			$newSessionsData=['userID'=>$userID, 'sessionID'=>$sessionID, 'tokenID'=>$tokenID];
			$sessionDetails[] = $newSessionsData;
		}
		file_put_contents("../models/sessionDetails.json", json_encode($sessionDetails));
	}
	
	static function checkTokenValidity($tokenID){
		$contentsFromFile = fopen("../models/sessionDetails.json","r");
		$nextLine = fgets($contentsFromFile);
		$sessionDetails = json_decode($nextLine, true);
		$currentSessionID = session_id();
		foreach($sessionDetails as $session) {
			if ($session['sessionID'] == $currentSessionID && $session['tokenID'] == $tokenID){
				return true;
			}
			else{
				return false;
			}
		}
	}
	
	static function logout($tokenID){
		$contentsFromFile = fopen("../models/sessionDetails.json","r");
		$nextLine = fgets($contentsFromFile);
		$sessionDetails = json_decode($nextLine, true);
		foreach($sessionDetails as $session)
			if ($session['tokenID']==$tokenID){
				$session['tokenID'] = "";
			}
		file_put_contents("../models/sessionDetails.json", json_encode($sessionDetails));
	}
}
?>