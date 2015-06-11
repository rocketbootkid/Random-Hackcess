<html>

<head>
	<title>Random Hackcess | Select Journey</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';

	addToDebugLog("journey.php, page, INFO");

	if ($_GET['journey_id']) { // Change Current Journey
		
		addToDebugLog("journey.php, change journey, INFO");
		
		changeJourney($_GET['player_id'], $_GET['character_id'], $_GET['journey_id']);
		
	} elseif ($_GET['create'] == "journey") { // Create a new journey
		
		addToDebugLog("journey.php, new journey, INFO");
		
		newJourney($_GET['player_id'], $_GET['character_id']);
		
	} else { // Display journeys
		
		addToDebugLog("journey.php, main, INFO");
		
		// Display character name
		$character_name = getCharacterDetails($_GET['character_id'], "character_name");
		echo "<h1 align=center>The Many Journeys of " . $character_name . "</h1>";
		
		// Display list of journeys
		if ($_GET['player_id'] && $_GET['character_id']) {
			journeySelect($_GET['player_id'], $_GET['character_id']);
		}

		echo "<p><div style='text-align: center;'><a href='character.php?player_id=" . $_GET['player_id'] . "'>Back to Character Select</a></div>";
		
		outputDebugLog();
	}
	
?>

</body>

</html>