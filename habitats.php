<?php
	include_once(dirname(__FILE__).'/database.php');
	include_once(dirname(__FILE__).'/error.php');
	date_default_timezone_set("America/New_York");

	$database = new Database;
	$error = new Error;
	$log = Error::getLog();
	$habitats = $database->database_get_habitats();
	if(!$habitats) {
		$log->error("Error getting habitatis.");
		http_response_code(500);
		exit();
	}

	while($row = pg_fetch_row($habitats)){
		print $row[1].":";
	}
?>
