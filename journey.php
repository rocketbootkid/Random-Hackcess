<html>

<head>
	<title>Random Hackcess | Select Journey</title>
	<!--<link rel="stylesheet" type="text/css" href="css/css.css">-->
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';


	if ($_GET['journey_id']) { // Change Current Journey
		changeJourney($_GET['player_id'], $_GET['character_id'], $_GET['journey_id']);
		//outputDebugLog();
	} elseif ($_GET['create'] == "journey") { // Create a new journey
		newJourney($_GET['player_id'], $_GET['character_id']);
	} else { // Display journeys
		// Display character name
		$character_name = getCharacterDetails($_GET['character_id'], "character_name");
		echo "<h1>The Many Journeys of " . $character_name . "</h1>";
		
		// Display list of journeys
		if ($_GET['player_id'] && $_GET['character_id']) {
			journeySelect($_GET['player_id'], $_GET['character_id']);
		}

		echo "<p><a href='character.php?player_id=" . $_GET['player_id'] . "'>Back to Character Select</a>";
		
		outputDebugLog();
	}
	
?>

</body>

</html>