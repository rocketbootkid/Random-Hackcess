<?php
	
	$debug_enabled = 0;
	$debug_level = "INFO";
	$debug_dirty_flag = 0;
	$debug_to_file = 1;

	function addToDebugLog($text) {
	
		global $debug_enabled;
		global $debug_log;
		global $debug_level;
		global $debug_dirty_flag;
		
		$timestamp_date = Date("Y-m-d");
		$timestamp_time = Date("H:m:s");
		$details = explode(', ', $text); // 0: function, 1: Message, 2: Level
		
		if ($details[2] == 'ERROR') {
			$color = '#E78585';
		} elseif ($details[2] == 'WARN') {
			$color = '#EDF775';
		} else {
			$color = '#fff';
		}
		
		$writeToLog = 0;
		
		switch($debug_level) {
			case "INFO": // Full Debug
				if ($debug_enabled == 1) { $writeToLog = 1; } // Always write to the log
				break;
			case "WARN": // Partial logging
				if (($details[2] == "ERROR" || $details[2] == "WARN") && $debug_enabled == 1) { // Only if entry is WARN or ERROR
					$writeToLog = 1;
				}		
				break;
			case "ERROR": // Only serious errors
				if ($details[2] == "ERROR" && $debug_enabled == 1) { // Only if entry is ERROR
					$writeToLog = 1;
				}			
				break;
		}
		
		if ($writeToLog == 1) {
			$debug_dirty_flag = 1;
			$debug_log = $debug_log . "\n\t<tr bgcolor=" . $color . ">\n\t\t<td width=80px>" . $timestamp_date . "\n\t\t<td width=50px>" . $timestamp_time . "\n\t\t<td id='1'>" . $details[0] . "\n\t\t<td>" . $details[1] . "\n\t\t<td align=center width=70px>" . $details[2] . "\n\t</tr>";
		}
	
	}
	
	function outputDebugLog() {
	
		global $debug_log;
		global $debug_enabled;
		global $debug_dirty_flag;
		global $debug_to_file;
	
		// Write this to file
		if ($debug_enabled == 1 && $debug_to_file == 1) {
			
			// Determine which page is writing this log; the page name will be the very first element in the $debug_log variable
			// find ".php", then work back and stop when reach ">" (the td tag)
			
			// Locate first instance of id=1
			$start = strpos($debug_log, "id='1'>");
			$end = strpos($debug_log, ".php");
			$length = $end - $start - 7;
			$page_name = substr($debug_log, $start+7, $length);
			
			// Format content
			$content = "<p>\n<table cellpadding=2 cellspacing=0 border=1 width=100%>\n\t<tr bgcolor=#bbb><td colspan=5 align=center>Debug Log</tr>" . $debug_log . "\n</table>";
			
			// Generate filename
			$timestamp = Date("Ymd_Hms");
			$file = "log/debug_" . $timestamp . "_" . $page_name . ".html";
			file_put_contents($file, $content);
		} elseif ($debug_enabled == 1 && $debug_dirty_flag == 1) {
			echo "<p>\n<table cellpadding=2 cellspacing=0 border=1 width=100%>\n\t<tr bgcolor=#bbb><td colspan=5 align=center>Debug Log</tr>" . $debug_log . "\n</table>";
		}
	
	}

	function outputQueryCount() {
	
		global $queries;
	
		echo "<p>Query count: " . $queries;
	
	}
	
	function outputQueryList() {
	
		global $query_sql;
	
		echo "<p>Query SQL: " . $query_sql;
	
	}
	
?>