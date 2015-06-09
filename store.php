<html>

<head>
	<title>Random Hackcess | Store</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	include 'functions/store_functions.php';
	include 'functions/effects_functions.php';

	addToDebugLog("store.php, page, INFO");
	
	$character_id = $_GET['character_id'];
	$enemy_id = $_GET['enemy_id'];
	$grid_id = $_GET['grid_id'];
	$player_id = $_GET['player_id'];
	$journey_id = $_GET['journey_id'];
	$action = $_GET['action'];
	$store_id = $_GET['store_id'];
	$item_id = $_GET['item_id'];
	
	if ($action == "sell") {
		// Sell item
		sellItem($store_id, $journey_id, $character_id, $player_id, $item_id);
		
	} elseif ($action == "buy") {
		// Buy item
		buyItem($store_id, $journey_id, $character_id, $player_id, $item_id);
		
	} elseif ($action == "equip") {
	
		// Equip the item
		equip($_GET['slot'], $_GET['item_id'], $character_id);
	
		// Redirect back to page
		echo "<script>window.location.href = 'store.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "'</script>";
		
	
	} else { // Display Store and Character items
		
		// Get store name
		$store_name = getStoreName($store_id);
		
		echo "<h1 align=center>Welcome to " . $store_name . "</h1>";
		
		echo "<table cellpadding=5 cellspacing=1 border=0 align=center><tr><td valign=top>";	
		characterEquipment($character_id, $player_id, $journey_id, $store_id);
		echo "<td valign=top>";
		storeEquipment($store_id, $journey_id, $character_id, $player_id);
		echo "</tr>";
		
		// Get weight of character equipment
		$equipment_total_weight = characterEquipmentWeight($character_id);
		addToDebugLog("store.php: - Equipment Weight: " . $equipment_total_weight);
		
		// Get character strength
		$character_strength = getCharacterDetailsInfo($character_id, 'strength');
		addToDebugLog("store.php: - Character Strength: " . $character_strength);
		
		echo "<tr><td align=center colspan=2>";
		if ($equipment_total_weight > $character_strength) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'>Back to Adventure</a>";
		} else {
			echo "You are carrying too much weight. You must sell items to continue.";
		}
		echo "</tr></table>";
		
		outputDebugLog();
		
	}

	
?>

</body>

</html>