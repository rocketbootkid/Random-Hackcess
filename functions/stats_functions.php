<?php

	function Wins() {
		
		// Returns most / least wins
		
		addToDebugLog("Wins(), Function Entry - no parameters, INFO");
		
		$sql = "SELECT character_id, count(*) AS count FROM hackcess.fight GROUP BY character_id ORDER BY count DESC LIMIT 1;";
		$result = search($sql); 
		
		$character_name = getCharacterDetails($result[0][0], "character_name");
		
		echo "<table cellpadding=3 cellspacing=0 align=center width=1000px border=0>";
		echo "<tr><td align=right width=500px>" . barGraph($result[0][1], "char", "Wins", "after") . "<td width=500px>" . $character_name . "</tr>";
		
		$sql = "SELECT character_id, count(*) AS count FROM hackcess.fight GROUP BY character_id ORDER BY count ASC LIMIT 1;";
		$result = search($sql);
		
		$character_name = getCharacterDetails($result[0][0], "character_name");

		echo "<tr><td width=500px align=right>" . $character_name . "<td align=left width=500px>" . barGraph($result[0][1], "enemy", "Wins", "after") . "</tr>";
				
		echo "</table>";
		
	}
	
	function Level() {
		
		// Returns highest / lowest level character
		
		addToDebugLog("Level(), Function Entry - no parameters, INFO");		
		
		$sql = "SELECT character.character_id, character_level FROM hackcess.character, hackcess.character_details WHERE character.character_id = character_details.character_id ORDER BY character_level DESC LIMIT 1;";
		$result = search($sql);
		
		$character_name = getCharacterDetails($result[0][0], "character_name");
		
		echo "<table cellpadding=3 cellspacing=0 align=center width=1000px border=0>";
		echo "<tr><td align=right width=500px>" . barGraph($result[0][1], "char", "Level", "before") . "<td width=500px>" . $character_name . "</tr>";
		
		$sql = "SELECT character.character_id, character_level FROM hackcess.character, hackcess.character_details WHERE character.character_id = character_details.character_id ORDER BY character_level ASC LIMIT 1;";
		$result = search($sql);
		
		$character_name = getCharacterDetails($result[0][0], "character_name");
		
		echo "<tr><td width=500px align=right>" . $character_name . "<td align=left width=500px>" . barGraph($result[0][1], "enemy", "Level", "before") . "</tr>";
		
		echo "</table>";	
		
	}
	
	function Journies() {
		
		// Returns longest / shortest journies
		
		addToDebugLog("Journies(), Function Entry - no parameters, INFO");
		
		$sql = "SELECT journey_id, count(*) AS a FROM hackcess.grid GROUP BY journey_id DESC ORDER BY a DESC LIMIT 1;";
		$result = search($sql);
		
		$journey_name = getJourneyDetails($result[0][0], "journey_name");
		
		echo "<table cellpadding=3 cellspacing=0 align=center width=1000px border=0>";
		echo "<tr><td align=right width=500px>" . barGraph($result[0][1], "char", "", "after") . "<td width=500px>" . $journey_name . "</tr>";
		
		$sql = "SELECT character_id, count(*) AS count FROM hackcess.fight GROUP BY character_id ORDER BY count ASC LIMIT 1;";
		$result = search($sql);
		
		$journey_name = getJourneyDetails($result[0][0], "journey_name");
		
		echo "<tr><td width=500px align=right>" . $journey_name . "<td align=left width=500px>" . barGraph($result[0][1], "enemy", "", "after") . "</tr>";
		
		echo "</table>";
		
	}
	
	function Gold() {

		// Returns most / least gold
		
		addToDebugLog("Gold(), Function Entry - no parameters, INFO");
		
		$sql = "SELECT character_id, gold FROM hackcess.character_details ORDER BY gold DESC LIMIT 1;";
		$result = search($sql);
		
		$character_name = getCharacterDetails($result[0][0], "character_name");
		
		echo "<table cellpadding=3 cellspacing=0 align=center width=1000px border=0>";
		echo "<tr><td align=right width=500px>" . $result[0][1] . " Gold<td width=500px>" . $character_name . "</tr>";
		
		$sql = "SELECT character_id, gold FROM hackcess.character_details WHERE gold > 0 ORDER BY gold ASC LIMIT 1;";
		$result = search($sql);
		
		$character_name = getCharacterDetails($result[0][0], "character_name");
		
		echo "<tr><td width=500px align=right>" . $character_name . "<td align=left width=500px>" . $result[0][1] . " Gold</tr>";
		
		echo "</table>";	
		
	}

?>