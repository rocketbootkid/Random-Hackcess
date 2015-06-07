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
				$item_name = "Red Potion Lvl " . $level;
				$item_ac_boost = 0;
				$item_attack_boost = 5;
				$item_weight = 1;
				$item_slot = "potion_attack";
				$item_cost = 100*$level; // When bought, the cost will determine how many turns it lasts.
				break;
			case 1: // HP
				srand(make_seed());
				$level = rand(1, 3);
				$item_name = "Green Potion Lvl " . $level;
				$item_ac_boost = 0;
				$item_attack_boost = 0;
				$item_weight = 1;
				$item_slot = "potion_hp";
				$item_cost = 100*$level; // When bought, the cost will determine how many turns it lasts.
				break;
			case 2: // AC
				srand(make_seed());
				$level = rand(1, 3);
				$item_name = "Blue Potion Lvl " . $level;
				$item_ac_boost = 5;
				$item_attack_boost = 0;
				$item_weight = 1;
				$item_slot = "potion_ac";
				$item_cost = 100*$level; // When bought, the cost will determine how many turns it lasts.
				break;
			case 3: // Strength
				srand(make_seed());
				$level = rand(1, 3);
				$item_name = "Yellow Potion Lvl " . $level;
				$item_ac_boost = 0;
				$item_attack_boost = 0;
				$item_weight = 1;
				$item_slot = "potion_strength";
				$item_cost = 100*$level; // When bought, the cost will determine how many turns it lasts.
				break;
		}
		
		// Add potion to store
		$dml = "INSERT INTO hackcess.store_contents (store_id, item_name, item_ac_boost, item_attack_boost, item_weight, item_slot, item_cost) VALUES (" . $store_id . ", '" . $name . "', " . $ac . ", " . $atk . ", " . $weight . ", '" . $slot  . "', " . $cost . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createPositiveEffect(), New potion added, INFO");
		} else {
			addToDebugLog("createPositiveEffect(), New potion not added, ERROR");
		}
		
	}
	
	function listCharacterEffects($character_id) {
	
		// This function lists all ongoing effects for the supplied character
	
		listCharacterEffects("createEffect(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
	
		$sql = "SELECT * FROM hackcess.effects WHERE character_id = " . $character_id . ";";
		addToDebugLog("listCharacterEffects(), Constructed query: " . $sql . ", INFO");
		$result = search($sql);
		$rows = count($result);
		if ($rows > 0) {
			for ($e = 0; $e < $rows; $e++) {
				echo $result[$][2] . ": " . $result[$e][4] . " to " . $result[$e][3] . " for " . $result[$e][5] . " moves.<br/>";
				// e.g. Poisoned: -5 to HP for 15 moves.
			}
		} else {
			echo "No ongoing effects.";
		}
	
	}
	
	function manageEffects($character_id) {
		
		// This function will manage the ongoing effects affecting a character

		addToDebugLog("manageEffects(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
		
		// Reduce the remaining duration by 1 for all ongoing effects
		
		
		
		
		// If an effect has reached zero duration, delete it
		
		
		
	}
	
	function removeEffect($effect_id) {
		
		// This function removes all effects for the supplied character
		
		listCharacterEffects("removeEffect(), Function Entry - supplied parameters: Effect ID: " . $effect_id . ", INFO");		
		
		$dml = "DELETE FROM hackcess.effects WHERE effect_id = " . $effect_id . ";";
		$result = delete($dml);
		if ($result == TRUE) {
			addToDebugLog("removeEffect(), Effect deleted from character, INFO");
		} else {
			addToDebugLog("removeEffect(), Effect not deleted from character, ERROR");
		}
		
	}
	
	function startEffect() {
	
		// This function will start new effects affecting the player, either when purchased or inflicted.
	
		addToDebugLog("createEffect(), Function Entry - supplied parameters: Store: " . $store_id . ", INFO");
	
	}

?>