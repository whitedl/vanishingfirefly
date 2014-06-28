<?php
	include(dirname(__FILE__).'/database.php');
	include(dirname(__FILE__).'/error.php');
	date_default_timezone_set("America/New_York");
 
	$database = new Database;
	$log = Error::getLog();
	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$log->info("Request from ".$_SERVER['REMOTE_ADDR']);
	}
	
	if(!isset($_POST['data'])){
		$log->error("Incorrect request made. No 'data' section.");
		$log->info("REQUEST: ".print_r($_POST,true));
		http_response_code(400);
		exit;
	}
	$data = json_decode($_POST['data'],true);	
	if(!isset($data['Time'], $data['Count'], $data['Latitude'], $data['Longitude'], $data['Habitat'], $data['Luma'], $data['Comments'], $data['Types'], $data['Ident'])){
		$log->error("Incorrect request made. Missing parameter.");
		$log->info("REQUEST: ".print_r($_POST,TRUE));
		$log->info("data: ".print_r($data,true));
		$log->info("Parameters: ".print_r(array_keys($data)));
		http_response_code(400);
		exit;
	}

	$hid = $database->database_get_habitat_id($data['Habitat']);	
       	if(empty($hid)) {
       		$log->error("Submitted habitat was not in database.\n\tAccepting data but not entering it into database.");
       	} else {
               	$my_id = $database->database_insert($data['Time'], $data['Count'], $data['Latitude'], $data['Longitude'], $hid, $data['Luma'], $data['Comments'], $data['Ident']);
               	$my_id_row = pg_fetch_row($my_id);
               	foreach($data['Types'] as $tid){
                	$database->database_insert_type($my_id_row[0], $tid);
               	}
               	if(isset($data['Address'])){
                       	$address = $data['Address'];
                       	$database->database_insert_address($my_id_row[0],$address['Street'], $address['City'], $address['State'], $address['Country']); 
               	}
	$log->info("All done!");
	}
?>
