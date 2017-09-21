<?php
/**
 * Author: Shing Sheung Daniel Ip
 * Purpose: part of the minimal php MVC framework for INFO20003
 *          this file contains a better driver for mysqli
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends mysqli{
	public function safe_query()
	{
		$numArgs = func_num_args();
		if($numArgs < 1){
			return FALSE;
		}

		$args = func_get_args();
		if(gettype($args[0]) !== "string"){
			return FALSE;
		}

		if(count($args) == 1){
			$formatted_query = $args[0];
		}else{
			$secure_array = array();
			array_push($secure_array, $args[0]);
			for ($i=1;$i<count($args);$i++) {
				$temp_escaped = $this->real_escape_string((string)$args[$i]);
				array_push($secure_array, $temp_escaped);
			}
			$formatted_query = call_user_func_array("sprintf", $secure_array);
		}
		echo "<div class=\"hidden\">" . $formatted_query . "</div>\n";
		return $this->query($formatted_query);
	}
}
?>