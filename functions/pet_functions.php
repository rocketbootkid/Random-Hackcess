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
		$cost = 5000 + ($pet_level * 500);
		
		// Add pet to store
		$dml = "INSERT INTO hackcess.store_contents (store_id, item_name, item_ac_boost, item_attack_boost, item_weight, item_slot, item_cost) VALUES (" . $store_id . ", '" . $pet_name . "', 0, 0, 0, '" . $pet_type  . "', " . $cost . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createNewPet(), New item added, INFO");
		} else {
			addToDebugLog("createNewPet(), New item not added, ERROR");
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




?>