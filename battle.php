<html>

<head>
	<title>Random Hackcess | Battle</title>
</head>

<body>

<h1 align=center>Ready for battle</h1>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	
	$player_id = $_GET['player_id'];
	$character_id = $_GET['character_id'];
	$journey_id = $_GET['journey_id'];
	$grid_id = $_GET['grid_id'];
	$enemy_id = $_GET['enemy_id'];

	if ($_GET['action'] == "create") {
	
		// Create feature record
		$feature_id = generateFeature($grid_id, "fight");
		addToDebugLog("battle.php: Feature ID: " . $feature_id);
		
		// Create Enemy
		$enemy_id = createEnemy($player_id, $journey_id, $character_id, $grid_id);
		addToDebugLog("battle.php: Enemy ID: " . $enemy_id);
		
		// Redirect to this page
		echo "<script>window.location.href = 'battle.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "&enemy_id=" . $enemy_id . "&grid_id=" . $grid_id . "'</script>";

	}
	
	if ($_GET['action'] == "flee") {
		
		// Get current grid id
		$grid_id = getCharacterCurrentGrid($character_id, $journey_id);
		
		// Handle the running away
		flee($character_id, $journey_id, $grid_id, $enemy_id);
		
		// Redirect back to the Adventure page
		echo "<script>window.location.href = 'adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'</script>";
		
	} else {
		
		// Get Character Stats
		$character_basic_info = getAllCharacterMainInfo($character_id);
		$character_detailed_info = getAllCharacterDetailedInfo($character_id);
		
		// Get Enemy Stats
		$enemy_info = getEnemyInfo($enemy_id);
	
		// Display Stats
		displayBattleStats($character_basic_info, $character_detailed_info, $enemy_info);
		
		// Show option: Fight or Run
		echo "<table cellpadding=3 cellspacing=0 border=0 style='margin-left: auto; margin-right: auto; margin-top: 20px;'>";
		echo "<tr><td width=200px align=center><h2><a href='fight.php?character_id=" . $character_id . "&enemy_id=" . $enemy_id . "&grid_id=" . $grid_id . "&player_id=" . $player_id . "&journey_id=" . $journey_id . "'>Fight!</a></h2>";
		echo "<td><h2>  OR  </h2>";
		echo "<td width=200px align=center><h2><a href='battle.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "&enemy_id=" . $enemy_id . "&action=flee'>Run!</a></h2></tr>";
		echo "</table>";
	
		//outputDebugLog();

	}
		
?>

</body>

</html>