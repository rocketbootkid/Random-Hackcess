<html>

<head>
	<title>Random Hackcess | Fight</title>
	<!--<link rel="stylesheet" type="text/css" href="css/css.css">-->
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';

	$character_id = $_GET['character_id'];
	$enemy_id = $_GET['enemy_id'];
	
	// Load Character Details
	$character_basic_info = getAllCharacterMainInfo($character_id);
	$character_name = trim($character_basic_info[0][2]); 		// 2	Name
	$character_role = $character_basic_info[0][3]; 		// 3	Role
	$character_level = $character_basic_info[0][4]; 	// 4	Level
	$character_status = $character_basic_info[0][7]; 	// 7	Status
	$character_detailed_info = getAllCharacterDetailedInfo($character_id);
	$character_hp = $character_detailed_info[0][2]; 	// 2	HP
	$character_atk = $character_detailed_info[0][3];	// 3	ATK
	$character_ac = $character_detailed_info[0][4];		// 4	AC
	$character_boosts = getCharacterBoosts($character_id);
	$character_ac_boost = $character_boosts[0];			// 0	AC Boost
	$character_atk_boost = $character_boosts[2];		// 1	ATK Boost
	
	// Load Enemy Details
	$enemy_info = getEnemyInfo($enemy_id);
	$enemy_name = $enemy_info[0][0];	// 0	Name
	$enemy_atk = $enemy_info[0][1];// 1	ATK
	$enemy_ac = $enemy_info[0][2];// 2	AC
	$enemy_hp = $enemy_info[0][3];// 3	HP

	echo "<table cellpadding=3 cellspacing=0 border=1 width=1200px>";
	echo "<tr><td>";
	echo "<td align=center><h2>" . $character_name . ", Level " . $character_level . " " . $character_role . "</h2>";
	echo "(HP: " . $character_hp . ", ATK: " . $character_atk . " + " . $character_atk_boost . ", AC: " . $character_ac . " + " . $character_ac_boost . ")";
	echo "<td align=center><h2>" . $enemy_name . "</h2>";
	echo "(HP: " . $enemy_hp . ", ATK: " . $enemy_atk . ", AC: " . $enemy_ac . ")</tr>";
	
	// Run fight
		// For each round, Character rand(0,ATK)+Boosts vs Enemy rand(0,AC)
			// if hit, damage = rand(0, ATK/4)
			// repeat for enemy
	$round = 0;
	while ($character_hp > 0 && $enemy_hp > 0) { // start round if both combatants still stand

		$round++;
		addToDebugLog("Fight.php: Round: " . $round);

		// Character attacks first
		$character_attack = rand(0, $character_atk) + $character_atk_boost;
		addToDebugLog("Fight.php: - Character Attack: " . $character_attack);
		$enemy_defend = rand(0, $enemy_ac);
		addToDebugLog("Fight.php: - Enemy Defend: " . $enemy_defend);
		
		echo "<tr><td>Round " . $round . "<td>" . $character_name . " attacks " . $enemy_name;
		
		if ($character_attack > $enemy_defend) { // Hit
			addToDebugLog("Fight.php: - Character hits Enemy");
			$enemy_damage = rand(1, ($character_atk + $character_atk_boost)/4);
			addToDebugLog("Fight.php: - Enemy takes damage: " . $enemy_damage);
			$enemy_hp = $enemy_hp - $enemy_damage;
			addToDebugLog("Fight.php: - Enemy HP reduced to: " . $enemy_hp);
			echo ", and hits for " . $enemy_damage . " points of damage!<br/>" . $enemy_name . " now has " . $enemy_hp . "HP";
		} else {
			echo ", and misses!<br/>" . $enemy_name . " still has " . $enemy_hp . "HP";
			addToDebugLog("Fight.php: - Enemy HP remains at: " . $enemy_hp);
		}
		
		// Check if enemy still standing
		if ($enemy_hp > 0) {
			
			$enemy_attack = rand(0, $enemy_atk);
			addToDebugLog("Fight.php: - Enemy Attack: " . $enemy_attack);
			$character_defend = rand(0, $character_ac + $character_ac_boost) ;
			addToDebugLog("Fight.php: - Character Defend: " . $character_defend);
			
			echo "<td>" . $enemy_name . " attacks " . $character_name;
			
			if ($enemy_attack > $character_defend) { // Hit
				addToDebugLog("Fight.php: - Enemy hits Character");
				$character_damage = rand(ceil($enemy_attack/4), $enemy_attack/2);
				addToDebugLog("Fight.php: - Character takes damage: " . $character_damage);
				$character_hp = $character_hp - $character_damage;
				addToDebugLog("Fight.php: - Character HP reduced to: " . $character_hp);
				echo ", and hits for " . $character_damage . " points of damage!<br/>" . $character_name . " now has " . $character_hp . "HP";
			} else {
				echo ", and misses!<br/>" . $character_name . " still has " . $character_hp . "HP";
				addToDebugLog("Fight.php: - Character HP remains at: " . $character_hp);
			}	
			echo "</tr>";

		} else {
			echo "<td>" . $enemy_name . " is defeated!</tr>";
		}
		
	}
	
	echo "<tr><td colspan=3 align=center><h2>";
	if ($character_hp <= 0) { // character died
		echo $character_name . " has been defeated! May his legend never die.";
	} else { // enemy died
		echo $enemy_name . " has been defeated, and good riddance to that scum!";
	}
	echo "</h2></tr></table>";
	
	// Record fight (new table)
	
	// if lose
		// Player dead
		
		
		// Create Descendant
		
		
		// Assign descendent some gold and best piece of equipment
		
	// if win
		// Kill enemy
		
		
		// Characters loses some HP
		
		
		// Give Gold / XP / random item
		
	
	
	outputDebugLog();
	
?>

</body>

</html>