<?php

class Core {

	// Function to write the index file
	function write_index() {

		// Config path
		$template_path 	= 'config/index.php';
		$output_path 	= '../index.php';

		// Open the file
		$index_file = file_get_contents($template_path); 

		// Write the new database.php file
		$handle = fopen($output_path,'w+');

		// Chmod the file, in case the user forgot
		@chmod($output_path,0777);

		// Verify file permissions
		if(is_writable($output_path)) {

			// Write the file
			if(fwrite($handle,$saved)) {
				@chmod($output_path,0644);
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}
	
	// Function to write the config file
	function write_config($installation_url, $enckey) {

		// Config path
		$template_path 	= 'config/config.php';
		$output_path 	= '../application/config/config.php';

		// Open the file
		$config_file = file_get_contents($template_path);

		$saved  = str_replace("%installation_url%",$installation_url,$config_file);
                $saved  = str_replace("%enckey%",$enckey,$saved);

		// Write the new config.php file
		$handle = fopen($output_path,'w+');

		// Chmod the file, in case the user forgot
		@chmod($output_path,0777);

		// Verify file permissions
		if(is_writable($output_path)) {

			// Write the file
			if(fwrite($handle,$saved)) {
				@chmod($output_path,0644);
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}
	
	// Function to write the database file
	function write_database($data) {

		// Config path
		$template_path 	= 'config/database.php';
		$output_path 	= '../application/config/database.php';

		// Open the file
		$database_file = file_get_contents($template_path);

		$saved  = str_replace("%db_hostname%",$data['db_hostname'],$database_file);
		$saved  = str_replace("%db_username%",$data['db_username'],$saved);
		$saved  = str_replace("%db_password%",$data['db_password'],$saved);
		$saved  = str_replace("%db_name%",$data['db_name'],$saved);

		// Write the new database.php file
		$handle = fopen($output_path,'w+');

		// Chmod the file, in case the user forgot
		@chmod($output_path,0777);

		// Verify file permissions
		if(is_writable($output_path)) {

			// Write the file
			if(fwrite($handle,$saved)) {
				@chmod($output_path,0644);
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}
 
	function create_rest_api_UV() {

		$path = "../assets/REST_API_UV.json";

		$handle = fopen($path, "w");

		if ($handle) {
			$content = '{ "version":"3.4", "url":"http://doorsoft.co/updater/irestora_plus/check_for_update.php"}';
			// Write the file
			if(fwrite($handle,$content)) {
				@chmod($output_path,0644);
				return true;
			} else {
				return false;
			}

			return true;
		}else{
			return false;
		}
	}

	function create_rest_api() {

		$path = "../assets/REST_API.json";

		$handle = fopen($path, "w");

		if ($handle) {
			$content = '{ "date":"'. date('Y-m-d') .'" }';
			// Write the file
			if(fwrite($handle,$content)) {
				@chmod($output_path,0644);
				return true;
			} else {
				return false;
			}

			return true;
		}else{
			return false;
		}
	}

	function create_rest_api_I($username, $purchase_code, $installation_url) {

		$path = "../assets/REST_API_I.json";

		$handle = fopen($path, "w");

		if ($handle) {
			$content = '{ "username":"'. $username .'", "purchase_code":"'.$purchase_code.'", "installation_url":"'.$installation_url.'" }';
			// Write the file
			if(fwrite($handle,$content)) {
				@chmod($output_path,0644);
				return true;
			} else {
				return false;
			}

			return true;
		}else{
			return false;
		}
	}
	
}