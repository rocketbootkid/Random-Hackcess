<?php

	function createNewPet($store_id) {
		
		// Creates a new pet
		
		addToDebugLog("createNewPet(), Function Entry - supplied parameters: Store ID: " . $store_id . ", INFO");
		
		// Get Pet Name
		$pet_name = generatePetName();

		// Determine Pet Type
		srand(make_seed());
		$pet_type = rand(1, 4);
		switch($pet_type) {
			case 1: // AC
				$pet_type = "pet_wolf";
				break;
			case 2: // ATK
				$pet_type = "pet_eagle";
				break;			
			case 3: // HP
				$pet_type = "pet_raven";
				break;
			case 4: // STR
				$pet_type = "pet_bear";
				break;
		}
		
		// Generate Stats
		srand(make_seed());
		$pet_level = rand(1, 5);
		$pet_xp = 1000 * $pet_level;
		$cost = 5000 + ($pet_level * 500);
		
		// Add pet to store
		$dml = "INSERT INTO hackcess.store_contents (store_id, item_name, item_ac_boost, item_attack_boost, item_weight, item_slot, item_cost) VALUES (" . $store_id . ", '" . $pet_name . "', 0, 0, 0, '" . $pet_type  . "', " . $cost . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createNewPet(), Pet added to store, INFO");
		} else {
			addToDebugLog("createNewPet(), Pet not added to store, ERROR");
		}
		
		// Add pet to pet database
		$dml = "INSERT INTO hackcess.pets (pet_name, pet_level, pet_type, pet_xp) VALUES ('" . $pet_name . "', " . $pet_level . ", " . $pet_type . ", " . $pet_xp . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createNewPet(), Pet record added, INFO");
		} else {
			addToDebugLog("createNewPet(), Pet record not added, ERROR");
		}
		
	}

	function generatePetName() {
		
		// Creates and returns a pet name
		
		addToDebugLog("generatePetName(), Function Entry - no parameters");
	
		// Generate the name
		srand(make_seed());
		$syllables = rand(1, 2);
		$text = "";
		for ($a = 1; $a <= $syllables; $a++) {
	
			$consonants = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "y", "z");
			$consonant = $consonants[rand(0, 20)];
	
			srand(make_seed());
			$numVowels = rand(1, 2);
			for ($v = 1; $v <= $numVowels; $v++) {			
			
				$vowels = array("a", "e", "i", "o", "u");
				srand(make_seed());
				$vowel = $vowels[rand(0, 4)];
				$text = $text . $vowel;
			
			}
	
			$name = $name . $consonant . $text;
			$text = "";
	
		}
		$name = ucfirst($name);
		addToDebugLog("generatePetName(), Name: " . $name . ", INFO");
	
		return $name;
		
	}

	function getPetBoost($character_id) {
		
		// Returns the boost provided by a pet
		
		addToDebugLog("getPetBoost(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");		
		
		$sql = "SELECT pet_level, pet_type FROM hackcess.pets WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		
		$pet_level = $result[0][0];
		$pet_type = $result[0][1];

		switch($pet_type) {
			case "pet_wolf": // AC
				$boost = "ac";
				break;
			case "pet_eagle": // ATK
				$boost = "atk";
				break;
			case "pet_raven": // HP
				$boost = "hp";
				break;
			case "pet_bear": // STR
				$boost = "str";
				break;
		}
		
		return array(
				"boost" => $boost,
				"amount" => $pet_level,
				);
		
	}
	
	function listCharacterPets($character_id) {
		
		// Lists character's pet details
		
		addToDebugLog("getPetBoost(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");		

		$sql = "SELECT * FROM hackcess.pets WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$rows = count($result);
		
		if ($rows > 0) {
			
			$type = explode("_", $result[0][3]);
			switch ($result[0][3]) {
				case "pet_wolf": // AC
					$boost = "ac";
					break;
				case "pet_eagle": // ATK
					$boost = "atk";
					break;
				case "pet_raven": // HP
					$boost = "hp";
					break;
				case "pet_bear": // STR
					$boost = "str";
					break;
			}
			
			return $result[0][1] . ", Lvl " . $result[0][2] . " " . ucfirst($type[1]) . " (+" . $result[0][2] . " " . strtoupper($boost) . "), " . $result[0][4] . "XP";
			
		}
		
	}
	
	function doesCharacterHavePet($character_id) {
		
		// Returns pet_id if character has a pet
		
		addToDebugLog("getPetBoost(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
		
		$sql = "SELECT pet_id FROM hackcess.pets WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$rows = count($result);
		
		if ($rows == 1) {
			return $result[0][0]; // Pet_Id
		} else {
			return 0;
		}
		
	}

	function getPetDetails($pet_id, $attribute) {
	
		// Returns selected pet attribute
	
		addToDebugLog("getPetDetails(), Function Entry - supplied parameters: Pet ID: " . $pet_id . "; Attribute: " . $attribute . ", INFO");
	
		$sql = "SELECT " . $attribute . " FROM hackcess.pets WHERE pet_id = " . $pet_id . ";";
		$result = search($sql);
	
		return $result[0][0];
	
	}
?>