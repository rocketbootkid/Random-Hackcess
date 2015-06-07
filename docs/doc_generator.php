<?php 

	ob_start();

	echo "<html><head><title>Random Hackcess Function List></title></head><body style='font-family: monospace;'>";
	
	// This page will crawl each of the function php files in the "functions" folder
	// It will extract the "function" line and the comment that follows it

	$files = scandir("../functions");
	$numFiles = count($files);
	
	echo "<table cellpadding=3 cellspacing=0 border=1 width=100%>";
	echo "<tr bgcolor=#999><td align=center colspan=3><h1>Function List</h1></tr>";
	
	for ($f = 2; $f < $numFiles; $f++) { // Start at 2 to miss off "." and "..".
		
		$path = "../functions/" . $files[$f];
		echo "<tr bgcolor=#bbb><td colspan=3><h3>" . $path;

		// Read file contents into an array
		$file_contents = file($path, FILE_SKIP_EMPTY_LINES);
		$file_numLines = count($file_contents);
		echo ", Lines: " . $file_numLines . "<h3>";
		
		// For each file, parse through for function declarations
		$function_count = 0;
		for ($l = 0; $l < $file_numLines; $l++) {
			if (substr($file_contents[$l], 0, 9) == "\tfunction") {
				$content = str_replace(' {', ' ', substr($file_contents[$l], 9));
				$content = str_replace(')', '', $content);
				$contents = explode('(', $content);
				$function_name = $contents[0];
				$variables = $contents[1];
				echo "<tr><td>" . $function_name . "<td>" . $variables;
				$function_count++;
				
				// Get following comment
				if (substr($file_contents[$l+2], 0, 4) == "\t\t//") {
					echo "<td><i>" . str_replace('// ', '', $file_contents[$l+2]) . "</i>";
				} else {
					echo "<td><i>No comment found.</i>";
				}
				
				echo "</tr>";
				
			}
			
		}
		echo "<tr bgcolor=#ddd><td colspan=3>" . $function_count . " functions found.</tr>";
		
	}
	
	echo "</body></html>";
	
	file_put_contents('api.html', ob_get_contents());
	
?> 