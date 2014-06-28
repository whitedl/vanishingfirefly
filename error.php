<?php
	class Error{	
		private static $log;
		public function getLog(){
			if(!isset($log)){
				include_once(dirname(__FILE__).'/log4php/Logger.php');
				Logger::configure('config.xml');
				$log = Logger::getLogger('myLogger');
			}
			return $log;
		}
	}
?>
