<?php

	function buildFamilyTrees() {
		
		// Creates a family tree table
		
		addToDebugLog("getZerothGen(), Function Entry - no parameters, INFO");
		
		$sql = "SELECT * FROM hackcess.character WHERE generation = 0;";
		$result = search($sql);
		$rows = count($result);
		
		$gen = 0;
		

		
		for ($z = 0; $z < $rows; $z++) {
			echo "<table cellpadding=3 cellspacing=0 border=1 align=center width=1000px>";
			echo "<tr bgcolor=#bbb><td align=center>Gen<td>Name<td align=right>Average Fight Length<td>Fights</tr>";
			$character_id = $result[$z][0];
			echo "<tr><td width=50px align=center>" . $gen . "<td>" . $result[$z][2] . "<td align=right width=300px>" . barGraph(getAverageRounds($character_id), 'char') . "<td width=300px align=left>" . barGraph(characterFightCount($character_id), "enemy") . "</tr>";

			$rows2 = 1;
			
			while ($rows2 == 1) {
				
				$gen++;
				
				$sql2 = "SELECT * FROM hackcess.character WHERE parent_id = " . $character_id . ";";
				$result2 = search($sql2);
				$rows2 = count($result2);
				if ($rows2 == 1) {
					echo "<tr><td width=50px align=center>" . $gen . "<td>" . $result2[0][2] . "<td align=right width=300px>" . barGraph(getAverageRounds($character_id), 'char') . "<td width=300px align=left>" . barGraph(characterFightCount($character_id), "enemy") . "</tr>";
					$character_id = $result2[0][0];
				}
				
			}
			echo "</table><P>";
			$gen = 0;
			
		}
		
	}
	
	function getAverageRounds($character_id) {
		
		// Returns the average number of rounds the character takes to win their fights
		
		addToDebugLog("getZerothGen(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
		
		$sql = "SELECT avg(rounds) FROM hackcess.fight WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		
		return round($result[0][0], 0);
		
	}

?>