<?php
	include(dirname(__FILE__).'/database.php');
	include(dirname(__FILE__).'/error.php');
	date_default_timezone_set("America/New_York");

	$database = new Database;
	$error = new Error;
	$log = Error::getLog();
	$types = $database->database_get_types();
	if(!$types) {
		$log->error("Error getting habitatis.");
		http_response_code(500);
		exit();
	}

	while($row = pg_fetch_row($types)){
		print $row[1].":";
	}
?>
