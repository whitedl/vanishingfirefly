<?php
	class Database {
		private function get_connection(){
			include(dirname(__FILE__).'/database_credentals.php');
			$log = Error::getLog();
			$connection_string = "host=".$database_host." port=".$database_port." dbname=".$database." user=".$database_user." password=".$database_password;
			$connection = pg_connect($connection_string);
			if(!$connection){
				http_response_code(500);
				$log->error("Error connecting to database.");
				exit;
			}
			return $connection;
		}

		private function print_error($connection){
			$log = Error::getLog();
			http_response_code(500);
			$log->error("Error querying database: " . pg_last_error($connection));
			pg_close($connection);
			exit;
		}

		function database_insert($time, $count, $latitude, $longitude, $habitat, $luma, $comment, $ident){
			$connection = self::get_connection();
			$comment = pg_escape_string($comment);
			$result = pg_query($connection, "INSERT INTO entries (id, time, count, latitude, longitude, habitat, luma, comment, ident) VALUES (nextval('firefly_id_serial'), '$time', '$count', '$latitude', '$longitude', '$habitat', '$luma', '$comment', '$ident') RETURNING id");
			if(!$result) self::print_error($connection);
			pg_close($connection);
			return $result;
		}

		function database_get_habitats(){
			$connection = self::get_connection();
			$result = pg_query($connection, "SELECT * FROM habitats ORDER BY hid ASC");
			if(!$result) self::print_error($connection);
			pg_close($connection);
			return $result;
		}

		function database_get_types(){
			$connection = self::get_connection();
			$result = pg_query($connection, "SELECT * FROM types ORDER BY tid ASC");
			if(!$result) self::print_error($connection);
			pg_close($connection);
			return $result;
		}
	
	
		function database_insert_type($eid, $tid){
			$connection = self::get_connection();
			$result = pg_query($connection, "INSERT INTO types_selected(tsid, eid, tid) VALUES (nextval('types_selected_id_serial'),'$eid', '$tid')");
			if(!$result) self::print_error($connection);
			pg_close($connection);

		}

		function database_get_habitat_id($habitat_string){
			$connection = self::get_connection();
			$result = pg_query($connection, "SELECT hid FROM habitats WHERE name = '$habitat_string'");
			if(!$result) self::print_error($connection);
			pg_close($connection);
			$row = pg_fetch_row($result);
			return $row[0];
		}

		function database_insert_address($eid, $street, $city, $state, $country){
			$connection = self::get_connection();
			if($state == 'none') $state = NULL;
			$city = pg_escape_string($city);
			$street = pg_escape_string($street);
			$result = pg_query($connection, "INSERT INTO addresses(eid, street, city, state, country) VALUES ('$eid', '$street', '$city', '$state', '$country')");
			if(!$result) self::print_error($connection);
			pg_close($connection);
		}
	}	
?>
