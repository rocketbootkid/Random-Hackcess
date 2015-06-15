<html>

<head>
	<title>Random Hackcess | Equipment</title>
</head>

<body>

<h1 align=center>Equipment</h1>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	include 'functions/effects_functions.php';
	include 'functions/trait_functions.php';
	
	addToDebugLog("equipment.php, page, INFO");
	
	$player_id = $_GET['player_id'];
	$character_id = $_GET['character_id'];
	$journey_id = $_GET['journey_id'];

	if ($_GET['action'] == "equip") {

		addToDebugLog("equipment.php, equip, INFO");
		
		// Equip the item		
		equip($_GET['slot'], $_GET['item_id'], $character_id);
		
		outputDebugLog();
		
		// Redirect to this page
		echo "<script>window.location.href = 'equipment.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'</script>";

	} elseif ($_GET['action'] == "drop") {
		
		addToDebugLog("equipment.php, drop, INFO");
		
		// Drop the item		
		drop($_GET['item_id']);
		
		outputDebugLog();
		
		// Redirect to this page
		echo "<script>window.location.href = 'equipment.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'</script>";
		
	} elseif ($_GET['action'] == "drink") {
		
		addToDebugLog("equipment.php, drink, INFO");
		
		// Drop the item		
		startEffect($_GET['item_id']);
		
		outputDebugLog();
		
		// Redirect to this page
		echo "<script>window.location.href = 'equipment.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'</script>";
		
	} else {

		if ($character_id > 0 && $player_id > 0 && $journey_id > 0) {

			addToDebugLog("equipment.php, main, INFO");
				
			// Display List of character equipment
			$value = manageEquipment($player_id, $character_id, $journey_id);
			
			if ($value == "ok") {
				echo "<p><div style='text-align: center;'><a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'>Back to Adventure</a></div>";
			} else {
				echo "<p><div style='text-align: center;'>You can't move. You need to drop something.</div>";
			}
			
			outputDebugLog();
			
		} else {
			echo "You fumble with the straps on your pack, but cannot open it. Guess the contents will have to remain a mystery.";
		}
	}
	
?>

</body>

</html>