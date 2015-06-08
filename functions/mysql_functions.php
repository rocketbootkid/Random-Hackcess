<?php

	// Global variables

	$connection = 0;
	$debug_log = "";
	$insystem_fuel_cost = 1;
	$outsystem_fuel_cost = 2;
	$current_player = 0;
	$queries = 0;
	$query_sql = "";
	$debug_enabled = 0;
	$debug_level = "INFO";
	$debug_dirty_flag = 0;

	// ********************************************************************************************************************************************
	// ************************************************ DATABASE-RELATED FUNCTIONS ****************************************************************
	// ********************************************************************************************************************************************	
	
	// connect()		Performs connection to MySQL
	// disconnect()		Performs disconnection from MySQL
	// search()			Performs SELECT of database using supplied SQL
	// insert()			Performs INSERT into database using supplied DML
	// insert()			Performs DELETE from database using supplied DML
	
	function connect() {

		// Connects to MySQL
	
		global $connection;
	
		//addToDebugLog("___- connect(): Connect to MySQL");
	
		$connection = mysql_connect('localhost', 'root', 'root');
		if (!$connection) {
			//echo "<script>window.reload();</script>";
			die("Could not connect: " . mysql_error() . "<script>window.reload();</script>");
		}
		
	}
	
	function disconnect() {
	
		// Disconnects from MySQL
		
		global $connection;
		
		//addToDebugLog("___- disconnect(): Disconnect from MySQL");
		
		mysql_close($connection);
	
	}

	function search($sql) {
	
		// Does MySQL SELECT operations, returning a results array
	
		addToDebugLog("___search(), Function Entry: supplied parameter: " . $sql . ", INFO");
	
		connect();

		global $connection;
		global $queries;
		global $query_sql;
		
		$result = mysql_query($sql, $connection);
		$queries = $queries + 1;
		$query_sql = $query_sql . "<br/>" . $sql;
		$rows = mysql_num_rows($result);
		if ($rows == "") {
			$rows = 0;
			addToDebugLog("___search(), Query returned " . $rows . " rows, WARN");
		} else {
			addToDebugLog("___search(), Query returned " . $rows . " rows, INFO");
		}
		
		$cols = mysql_num_fields($result);
		$array = Array();
		
		for ($r = 0; $r < $rows; $r++) {
			$row_array = mysql_fetch_row($result);
			for ($c = 0; $c < $cols; $c++) {
				$array[$r][$c] = $row_array[$c];
			}			
		}
		
		disconnect();
	
		return $array;
	
	}
	
	function insert($dml) {

		// Does MySQL INSERT and UPDATE operations, returning a boolean flag
	
		addToDebugLog("___insert(), Function Entry: supplied parameter: " . $dml . ", INFO");
	
		connect();

		global $connection;
		
		$result = mysql_query($dml, $connection);
		$status = $result;
	
		if ($status == TRUE) {
			addToDebugLog("___insert(), Insert operation successful, INFO");
		} else {
			addToDebugLog("___insert(), Insert operation failed: " . mysql_error($connection) . ", ERROR");
		}
	
		disconnect();
	
		return $status;
	
	}
	
	function delete($dml) {
	
		// Does MySQL DELETE operations, returning a boolean flag
	
		addToDebugLog("___delete(), Function Entry: supplied parameter: " . $dml . ", INFO");
	
		connect();

		global $connection;
		
		$result = mysql_query($dml, $connection);
		$status = $result;
	
		if ($status == TRUE) {
			addToDebugLog("___delete(), Delete operation successful, INFO");
		} else {
			addToDebugLog("___delete(), Delete operation failed: " . mysql_error($connection) . ", ERROR");
		}
	
		disconnect();
	
		return $status;	

	}

?>