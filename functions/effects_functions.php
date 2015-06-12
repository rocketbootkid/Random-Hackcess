<?php

	function createPositiveEffect($store_id) {
		
		// Creates a new positive effect potion in a store
		
		addToDebugLog("createPositiveEffect(), Function Entry - supplied parameters: Store: " . $store_id . ", INFO");
		
		// Red potion: ATK
		// Green potion: HP
		// Blue potion: AC
		// Yellow potion: STR
		
		srand(make_seed());
		$item_choice = rand(0, 3);
		
		switch ($item_choice) {
			case 0: // ATTACK
				srand(make_seed());
				$level = rand(1, 3);
				$item_name = "Attack Potion Lvl " . $level;
				$item_ac_boost = 0;
				$item_attack_boost = 5;
				$item_weight = 0;
				$item_slot = "potion_attack";
				$item_cost = 100*$level; // When bought, the cost will determine how many turns it lasts.
				break;
			case 1: // HP
				srand(make_seed());
				$level = rand(1, 3);
				$item_name = "Health Potion Lvl " . $level;
				$item_ac_boost = 0;
				$item_attack_boost = 0;
				$item_weight = 0;
				$item_slot = "potion_hp";
				$item_cost = 100*$level; // When bought, the cost will determine how many turns it lasts.
				break;
			case 2: // AC
				srand(make_seed());
				$level = rand(1, 3);
				$item_name = "Defence Potion Lvl " . $level;
				$item_ac_boost = 5;
				$item_attack_boost = 0;
				$item_weight = 0;
				$item_slot = "potion_ac";
				$item_cost = 100*$level; // When bought, the cost will determine how many turns it lasts.
				break;
			case 3: // Strength
				srand(make_seed());
				$level = rand(1, 3);
				$item_name = "Strength Potion Lvl " . $level;
				$item_ac_boost = 0;
				$item_attack_boost = 0;
				$item_weight = 0;
				$item_slot = "potion_strength";
				$item_cost = 100*$level; // When bought, the cost will determine how many turns it lasts.
				break;
		}
		
		// Add potion to store
		$dml = "INSERT INTO hackcess.store_contents (store_id, item_name, item_ac_boost, item_attack_boost, item_weight, item_slot, item_cost) VALUES (" . $store_id . ", '" . $item_name . "', " . $item_ac_boost . ", " . $item_attack_boost . ", " . $item_weight . ", '" . $item_slot  . "', " . $item_cost . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createPositiveEffect(), New potion added, INFO");
		} else {
			addToDebugLog("createPositiveEffect(), New potion not added, ERROR");
		}
		
	}
	
	function createNegativeEffect($character_id) {
	
		// Creates a new positive effect potion in a store
	
		addToDebugLog("createNegativeEffect(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");

		srand(make_seed());
		$negative_effect = rand(0, 12); // Determine if a negative effect begins.
		
		if ($negative_effect == 6) {
		
			srand(make_seed());
			$item_choice = rand(0, 3);
		
			switch ($item_choice) {
				case 0: // Affects ATK
					srand(make_seed());
					$level = rand(1, 3);
					$effect_name = "Clumsy Lvl " . $level;
					$affects = "atk";
					$amount = 0 - $level;
					$duration = 10;
					break;
				case 1: // Affects HP
					srand(make_seed());
					$level = rand(1, 3);
					$effect_name = "Weakened Lvl " . $level;
					$affects = "hp";
					$amount = 0 - $level;
					$duration = 10;
					break;
				case 2: // Affects AC
					srand(make_seed());
					$level = rand(1, 3);
					$effect_name = "Off-guard Lvl " . $level;
					$affects = "ac";
					$amount = 0 - $level;
					$duration = 10;
					break;
				case 3: // Affects STR
					srand(make_seed());
					$level = rand(1, 3);
					$effect_name = "Weakened Potion Lvl " . $level;
					$affects = "str";
					$amount = 0 - $level;
					$duration = 10;
					break;
			}
			
			// Create journal entry
			$details = "Fought and beat " . $enemy_name . " in " . $round . " rounds.";
			$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", '" . $details . "');";
			$result_m = insert($dml);
			if ($result_m == TRUE) {
				addToDebugLog("doFight(), Journal entry added, INFO");
			} else {
				addToDebugLog("doFight(), Journal entry not added, ERROR");
			}
			
			// Get effect id to return
			$sql = "SELECT effect_id FROM hackcess.effects WHERE character_id = " . $character_id . " ORDER BY effect_id DESC LIMIT 1;";
			$result = search($sql);
			$rows = count($result);
			
			return $result[0][0];
		
		}
	
	}
	
	function listCharacterEffects($character_id) {
	
		// This function lists all ongoing effects for the supplied character
	
		addToDebugLog("createEffect(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
	
		$sql = "SELECT * FROM hackcess.effects WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$rows = count($result);
		if ($rows > 0) {
			
			echo "<h3>Ongoing Effects</h3>";
			
			for ($e = 0; $e < $rows; $e++) {
				if ($result[$e][4] > 0) { echo "+"; }
				echo $result[$e][4] . " to " . strtoupper($result[$e][3]) . " for " . $result[$e][5] . " more moves.<br/>";
			}
		}
	}
	
	function manageEffects($character_id) {
		
		// This function will manage the ongoing effects affecting a character

		addToDebugLog("manageEffects(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
		
		// Reduce the remaining duration by 1 for all ongoing effects
		$dml = "UPDATE hackcess.effects SET duration = duration - 1 WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("manageEffects(), Effects updated, INFO");
		} else {
			addToDebugLog("manageEffects(), Effects not updated, ERROR");
		}
		
		// If an effect has reached zero duration, delete it
		$sql = "SELECT effect_id FROM hackcess.effects WHERE character_id = " . $character_id . " AND duration <= 0;";
		$result = search($sql);
		$rows = count($result);
		for ($e = 0; $e < $rows; $e++) {	
			removeEffect($result[$e][0]);
		}
		
	}
	
	function deleteAllEffects($character_id) {

		// This function will delete all ongoing effects for a character
		
		addToDebugLog("deleteAllEffects(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
	
		$sql = "SELECT effect_id FROM hackcess.effects WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$rows = count($result);
		for ($e = 0; $e < $rows; $e++) {
			removeEffect($result[$e][0]);
		}	

	}
	
	function removeEffect($effect_id) {
		
		// This function removes all effects for the supplied character
		
		addToDebugLog("removeEffect(), Function Entry - supplied parameters: Effect ID: " . $effect_id . ", INFO");		
		
		$dml = "DELETE FROM hackcess.effects WHERE effect_id = " . $effect_id . ";";
		$result = delete($dml);
		if ($result == TRUE) {
			addToDebugLog("removeEffect(), Effect deleted from character, INFO");
		} else {
			addToDebugLog("removeEffect(), Effect not deleted from character, ERROR");
		}
		
	}
	
	function startEffect($item_id) {
	
		// This function will start new effects affecting the player, either when purchased or inflicted.
		
		addToDebugLog("startEffect(), Function Entry - supplied parameters: Item ID: " . $item_id . ", INFO");
		
		// Get potion details
		$potion_details = getEquipmentDetails($item_id);
		// 0: equipment_id
		// 1: name
		// 2: ac_boost
		// 3: attack_boost
		// 4: weight
		// 5: slot
		// 6: character_id
		
		$potion_name = explode(' ', $potion_details[0][1]);
		$level = $potion_name[3]; 
		// Determine Effect Name based on potion colour
		switch($potion_name[0]) {
			case "Attack": // Attack
				$affects = "atk";
				$effect_name = "Attack Level " . $level;
				$amount = $level;
				$duration = 10;
				break;
			case "Defence": // AC
				$affects = "ac";
				$effect_name = "Defence Level " . $level;
				$amount = $level;
				$duration = 10;
				break;			
			case "Health": // HP
				$affects = "hp";
				$effect_name = "Health Level " . $level;
				$amount = $level;
				$duration = 10;
				break;
			case "Strength": // Strength
				$affects = "str";
				$effect_name = "Strength Level " . $level;
				$amount = $level;
				$duration = 10;
				break;
		}
		
		// Create effect in effects db
		$dml = "INSERT INTO hackcess.effects (character_id, effect_name, affects, amount, duration) VALUES (" . $potion_details[0][6] . ", '" . $effect_name . "', '" . $affects . "', " . $amount . ", " . $duration . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("startEffect(), New effect added, INFO");
			
			// Delete potion from character equipment
			$dml = "DELETE FROM hackcess.character_equipment WHERE equipment_id = " . $item_id . " AND character_id = '" . $potion_details[0][6] . "';";
			$result = delete($dml);
			if ($result == TRUE) {
				addToDebugLog("startEffect(), Item deleted, INFO");
			} else {
				addToDebugLog("startEffect(), Item not deleted, ERROR");
			}
			
		} else {
			addToDebugLog("startEffect(), New effect not added, ERROR");
		}		
		
	}

	function getEquipmentDetails($item_id) {
	
		// This function returns details for the piece of equipment
	
		addToDebugLog("getEquipmentDetails(), Function Entry - supplied parameters: Item ID: " . $item_id . ", INFO");
	
		$sql = "SELECT * FROM hackcess.character_equipment WHERE equipment_id = " . $item_id . ";";
		$result = search($sql);
	
		return $result;
	
	}
	
	function getEffectBoosts($character_id) {
		
		// This function will return the total stat boosts from ongoing effects
		
		addToDebugLog("getEffectBoosts(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");		
		
		$sql = "SELECT affects, amount FROM hackcess.effects WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$rows = count($result);
		if ($rows > 0) {
			
			$ac_boost = 0;
			$atk_boost = 0;
			$hp_boost = 0;
			$str_boost = 0;
			
			for ($e = 0; $e < $rows; $e++) {
				switch($result[$e][0]) {
					case "ac": // AC
						$ac_boost = $ac_boost + $result[$e][1]; 
						break;
					case "atk": // ATK
						$atk_boost = $atk_boost + $result[$e][1];
						break;					
					case "hp": // HP
						$hp_boost = $hp_boost + $result[$e][1];
						break;					
					case "str": // STR
						$str_boost = $str_boost + $result[$e][1];
						break;						
				}
			}
			$boosts = array("ac" => $ac_boost,
						"atk" => $atk_boost,
						"hp" => $hp_boost, 
						"str" => $str_boost
			);
			addToDebugLog("getEffectBoosts(), AC Boost: " . $ac_boost . "; ATK Boost: " . $atk_boost . "; HP Boost: " . $hp_boost . "; STR Boost: " . $str_boost . ", INFO");
			
			return $boosts;
		} else {
			return 0;
		}
		
	}
	
	function getEffectDetails($effect_id) {
		
		// This function will return details about the effect.
		
		addToDebugLog("startEffect(), Function Entry - supplied parameters: Effect ID: " . $effect_id . ", INFO");
		
		// Get effect id to return
		$sql = "SELECT * FROM hackcess.effects WHERE effect_id = " . $effect_id . ";";
		$result = search($sql);
		$rows = count($result);
			
		return $result;		

	}
	
	function randomPotionDrop($character_id) {
	
		// Creates and returns a store name
	
		addToDebugLog("randomPotionDrop(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Journey ID: " . $journey_id . "; Character ID: " . $charcter_id . "; Store ID: " . $store_id . ", INFO");
	
		srand(make_seed());
		$drop_potion = rand(0, 10);
		
		if ($drop_potion == 5) {
		
			srand(make_seed());
			$item_choice = rand(0, 3);
			
			switch ($item_choice) {
				case 0: // ATTACK
					srand(make_seed());
					$level = rand(1, 3);
					$item_name = "Attack Potion Lvl " . $level;
					$item_ac_boost = 0;
					$item_attack_boost = 5;
					$item_weight = 0;
					$item_slot = "potion_attack";
					break;
				case 1: // HP
					srand(make_seed());
					$level = rand(1, 3);
					$item_name = "Health Potion Lvl " . $level;
					$item_ac_boost = 0;
					$item_attack_boost = 0;
					$item_weight = 0;
					$item_slot = "potion_hp";
					break;
				case 2: // AC
					srand(make_seed());
					$level = rand(1, 3);
					$item_name = "Defence Potion Lvl " . $level;
					$item_ac_boost = 5;
					$item_attack_boost = 0;
					$item_weight = 0;
					$item_slot = "potion_ac";
					break;
				case 3: // Strength
					srand(make_seed());
					$level = rand(1, 3);
					$item_name = "Strength Potion Lvl " . $level;
					$item_ac_boost = 0;
					$item_attack_boost = 0;
					$item_weight = 0;
					$item_slot = "potion_strength";
					break;
			}	
			
			// Add potion to character equipment
			$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('" . $item_name . "', " . $item_ac_boost . ", " . $item_attack_boost . ", " . $item_weight . ", '" . $item_slot . "', " . $character_id . ");";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("randomPotionDrop(), Item added to player inventory, INFO");
			} else {
				addToDebugLog("randomPotionDrop(), Item not added to player inventory, ERROR");
			}
			
			return $item_name;
					
		} else {
			return "";
		}
	
	}
	
?>