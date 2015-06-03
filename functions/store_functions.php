<?php

	function characterEquipment() {
		
		
		
	}

	function storeEquipment() {
		
		
		
	}
	
	function generateStore($grid_id, $journey_id, $character_id) {
		
		// Creates and returns a store name
		
		addToDebugLog("generateStore(): Function Entry - Parameters: Grid ID: " . $grid_id . ", Journey ID: " . $journey_id . ", Character ID: " . $character_id);
		
		// Store will have 0-2 of each item
		// Items will be range from lowest modifier of existing items, to 5 levels above the highest modifier
		// Cost of an item will be 50 x the modifier value
		
		// Generate Store Name
		$store_name = generateStoreName();
		
		// Create store
		$dml = "INSERT INTO hackcess.store (store_name, grid_id, journey_id, character_id) VALUES ('" . $store_name . "', " . $grid_id . ", " . $journey_id . ", " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("generateStore(): New journal entry added");
		} else {
			addToDebugLog("generateStore(): ERROR: New journal entry not added");
		}
		
		// Get store ID
		$sql = "SELECT store_id FROM hackcess.store ORDER BY store_id DESC LIMIT 1;";
		addToDebugLog("generateStore(): Constructed query: " . $sql);
		$result = search($sql);
		$store_id = $result[0][0];
		
		// Populate with items
		
		// Get character lowest rank equipped item
		$sql = "SELECT * FROM hackcess.character_equipment WHERE character_id = " . $character_id . ";";
		addToDebugLog("generateStore(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);
		
		$min_ac = 1000;
		$min_atk = 1000;
		for ($i = 0; $i < $rows; $i++) {
			$is_equipped = isEquipped($result[$i][5], $result[$i][0], $character_id);
			if ($is_equipped == 1 && $result[$i][2] > 0 && $result[$i][2] < $min_ac) { //AC
				$min_ac = $result[$i][2];
			}
			if ($is_equipped == 1 && $result[$i][3] > 0 && $result[$i][3] < $min_atk) { //AC
				$min_atk = $result[$i][3];
			}			
		}

		// Head Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('head', $store_id, $min_ac);
		}

		// Chest Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('chest', $store_id, $min_ac);
		}
		
		// Legs Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('legs', $store_id, $min_ac);
		}
		
		// Shield Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('shield', $store_id, $min_ac);
		}
		
		// Sword Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('weapon', $store_id, $min_atk);
		}
		
	}
	
	function sellItem() {
		
		// Remove item from player equipment
		
		// Add value to player gold
		
		// Add item to store
		
		
		
	}
	
	function buyItem() {
		
		// Remove item from store
		
		// Remove gold from player
		
		// Add item to player equipment
		
	}
	
	function generateStoreName() {

		// Creates and returns a store name
	
		addToDebugLog("generateEnemyName(): Function Entry - no parameters");
	
		// Generate the name
		$syllables = rand(2, 4);
		$name = "";
		for ($a = 0; $a < $syllables; $a++) {
	
			$consonants = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "y", "z");
			$consonant = $consonants[rand(0, 20)];
				
			$vowels = array("a", "e", "i", "o", "u");
			$vowel = $vowels[rand(0, 4)];
				
			$name = $name . $consonant . $vowel;
	
		}
		$name = ucfirst($name);
		addToDebugLog("generateStoreName(): Name: " . $name);
	
		$final_name = $name . "^apos;s Store";
		addToDebugLog("generateStoreName(): Final name: " . $final_name);
	
		return $final_name;
		
	}
	
	function createStoreItem($slot, $store_id, $min_level) {
	
		// This function equips the item selected
	
		addToDebugLog("createRandomItem(): Function Entry - supplied parameters: Store ID: " . $store_id . ", Slot: " . $slot . ", Min Level: " . $min_level);
	
		srand(make_seed());
		$item_choice = rand(0, 4);
	
		switch ($item_choice) {
			case 0: // Head
				$slot = "head";
				$name = "Helm";
				$ac = rand($min_level, $min_level + 5);
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50*$ac;
				break;
			case 1: // Chest
				$slot = "chest";
				$name = "Chestplate";
				$ac = rand($min_level, $min_level + 5);
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50*$ac;
				break;
			case 2: // Legs
				$slot = "legs";
				$name = "Trousers";
				$ac = rand($min_level, $min_level + 5);
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50 * intval($ac);
				break;
			case 3: // Shield
				$slot = "shield";
				$name = "Shield";
				$ac = rand($min_level, $min_level + 5);
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50*$ac;
				break;
			case 4: // Weapon
				$slot = "weapon";
				$name = "Sword";
				$atk = rand($min_level, $min_level + 5);
				$weight = round($atk/2);
				$ac = 0;
				$cost = 50*$atk;
				break;
		}
	
		// Add weapon to character
		$dml = "INSERT INTO hackcess.store_contents (store_id, item_name, item_ac_boost, item_attack_boost, item_weight, item_slot, item_cost) VALUES (" . $store_id . ", '" . $name . "', " . $ac . ", " . $atk . ", " . $weight . ", '" . $slot  . "', " . $cost . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createRandomItem(): New item added");
		} else {
			addToDebugLog("createRandomItem(): ERROR: New item not added");
		}
	
		return $details;
	
	}
	
?>