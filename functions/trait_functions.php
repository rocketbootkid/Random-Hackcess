<?php
	
	function selectCharacterTraits($character_id, $predecessor_id) {
		
		// This function handles the traits a character inherits; one is copied, one is mutated
		
		addToDebugLog("selectCharacterTraits(), Function Entry - Parameters: Character ID: " . $character_id . "; Predecessor ID: " . $predecessor_id . ", INFO");
		
		if ($predecessor_id != 0) { // If there's a predecessor, use them

			// Select random trait from predecessor
			$sql = "SELECT trait_id FROM hackcess.traits WHERE character_id = " . $predecessor_id . ";";
			$result = search($sql);
			$rows = count($result);
			
			if ($rows != 0) {
			
				// Choose which trait to keep
				srand(make_seed());
				$trait_select = rand(0, 1);
				if ($trait_select == 0) {
					$keep_trait_id = $result[0][0];
					$mutate_trait_id = $result[1][0];
				} else {
					$keep_trait_id = $result[1][0];
					$mutate_trait_id = $result[0][0];
				}
				
				// Copy selected trait; simply update the existing trait to apply to the new character
				$dml = "UPDATE hackcess.traits SET character_id = " . $character_id . " WHERE trait_id = " . $keep_trait_id . ";";
				$result = insert($dml);
				if ($result == TRUE) {
					addToDebugLog("selectCharacterTraits(), Trait updated, INFO");
				} else {
					addToDebugLog("selectCharacterTraits(), Trait not updated, ERROR");
				}
				
				// Determine whether to strengthen or weaken the other stat
				srand(make_seed());
				$value_select = rand(0, 1);
				if ($value_select == 0) { $magnitude = -1; } else { $magnitude = 1; }
	
				// Get the magnitude of the trait
				$sql = "SELECT magnitude FROM hackcess.traits WHERE trait_id = " . $mutate_trait_id . ";";
				$result = search($sql);
				if ($result[0][0] == 1 && $magnitude = -1) { $magnitude = -2; } // If mutation would zero the stat, ensure it flips to the opposite
				if ($result[0][0] == -1 && $magnitude = 1) { $magnitude = 2; } // If mutation would zero the stat, ensure it flips to the opposite
	
				// Mutate the other trait, and update it for the new character
				$dml = "UPDATE hackcess.traits SET character_id = " . $character_id . ", magnitude = magnitude + " . $magnitude . " WHERE trait_id = " . $mutate_trait_id . ";";
				$result = insert($dml);
				if ($result == TRUE) {
					addToDebugLog("selectCharacterTraits(), Trait updated, INFO");
				} else {
					addToDebugLog("selectCharacterTraits(), Trait not updated, ERROR");
				}
				
			} else { // Predecessor had no traits, so create them
				createRandomTrait($character_id, 2);
			}
			
		} else { // If there's no predecessor, create 2 new traits
			createRandomTrait($character_id, 2);
		}
	
	}

	function getAllTraitDetails($trait_id) {
		
		// This function returns all details of a character trait
		
		addToDebugLog("getAllTraitDetails(), Function Entry - Parameters: Trait ID: " . $trait_id . ", INFO");
		
		$sql = "SELECT * FROM hackcess.traits WHERE trait_id = " . $trait_id . ";";
		$result = search($sql);
		
		return $result; // 0: id, 1: character_id, 2: trait_type, 3: magnitude
		
	}
	
	function createRandomTrait($character_id, $number) {
		
		// This function creates a number of random traits for a character
		
		addToDebugLog("selectCharacterTraits(), Function Entry - Parameters: Character ID: " . $character_id . "; Number: " . $number . ", INFO");
		
		for ($n = 0; $n < $number; $n++) {
			
			// Determine whether the trait strengthens or weakens the stat
			srand(make_seed());
			$value_select = rand(0, 1);
			if ($value_select == 0) { $magnitude = -1; } else { $magnitude = 1; }

			srand(make_seed());
			$trait_select = rand(0, 3);
				
			switch($trait_select) {
				case 0: // AC
					$type = "ac";
					break;
				case 1: // ATK
					$type = "atk";
					break;					
				case 2: // HP
					$type = "hp";
					break;
				case 3: // STR
					$type = "str";
					break;
			}
			
			// Add trait
			$dml = "INSERT INTO hackcess.traits (character_id, trait_type, magnitude) VALUES (" . $character_id . ", '" . $type . "', " . $magnitude . ");";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("createRandomTrait(), Trait added, INFO");
			} else {
				addToDebugLog("createRandomTrait(), Trait not added, ERROR");
			}		

		}
		
	}

	function getTraitBoosts($character_id) {
		
		// This function returns the traits boosts for the character
		
		addToDebugLog("selectCharacterTraits(), Function Entry - Parameters: Character ID: " . $character_id . ", INFO");		
		
		$sql = "SELECT trait_type, magnitude FROM hackcess.traits WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		
		$ac_boost = 0;
		$atk_boost = 0;
		$hp_boost = 0;
		$str_boost = 0;
		
		for ($b = 0; $b < 2; $b++) {
			$boost_type = $result[$b][0];
			$magnitude = $result[$b][1];
			
			switch($boost_type) {
				case "ac":
					$ac_boost = $ac_boost + $magnitude;
					break;
				case "atk":
					$atk_boost = $atk_boost + $magnitude;
					break;
				case "hp":
					$hp_boost = $hp_boost + $magnitude;
					break;
				case "str":
					$str_boost = $str_boost + $magnitude;
					break;
			}
		}
		
		addToDebugLog("getTraitBoosts(), AC Boost: " . $ac_boost . "; ATK Boost: " . $atk_boost . "; HP Boost: " . $hp_boost . "; STR Boost: " . $str_boost . ", INFO");
		
		$boosts = array("ac" => $ac_boost,
						"atk" => $atk_boost,
						"hp" => $hp_boost,
						"str" => $str_boost
				);
		
		return $boosts;
		
		
	}

?>