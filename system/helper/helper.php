<?php
/**
 * Author: Shing Sheung Daniel Ip
 * Purpose: part of the minimal php MVC framework for INFO20003
 *          this file loads up the route.php and process all the routes
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Functions to check file existance and
 * safely load the file, if file is not found, then raise http error
 */
function file_exists_fatal($file_path)
{
	if(file_exists($file_path) == false)
	{
		// controller not found
		raise_error(500, '500 internal server error<br>');
	}
	else
	{
		return true;
	}
};

function safe_include($file_path)
{
	if(file_exists_fatal($file_path)){
		include $file_path;
	}
};

/**
 * Function that raise http error
 */
function raise_error($err_code, $err_msg)
{
	echo $err_msg;
	exit();
}

/**
 * A function that parse the session cookie
 */
function get_sess_cookie()
{
	// check if the key is set, if not, no session data, which is empty array
	$sess_name = $GLOBALS['config']['sess_prefix'] . '_SESSION';
	if(!isset($_COOKIE[$sess_name])){
		return array();
	}else{
		$sess_cookie_str = $_COOKIE[$sess_name];
	}

	$delim_pos = strpos($sess_cookie_str, '-');
	$hashed = '';
	$sess_str = '';
	if($delim_pos !== false and $delim_pos === 64)
	{
		$hashed = substr($sess_cookie_str, 0, 64);
		$sess_str = substr($sess_cookie_str, 65);
	}

	// something is going wrong if there is a hash, but no session body
	// as it is suspicious, just return no session data as well
	if($sess_str == ''){
		return array();
	}

	// very suspicious, the hashed with the original hashed are not matched
	// don't parse any session, just say there's no session data
	$valid_hashed = hash('sha256', $sess_str . $GLOBALS['config']['secret']);
	if(strcmp($hashed, $valid_hashed) != 0){
		return array();
	}

	return parse_sess_str($sess_str);
}

/**
 * A function to just parse the session part str
 */
function parse_sess_str($sess_str)
{
	$parsed = json_decode($sess_str, true);
	if(hexdec($parsed['_TS']) < time()){
		return array();
	}
	return $parsed;
}

/**
 * A function to put the session back to the cookie, and secure it
 */
function sess2cookie($session)
{
	$exp_time = time() + $GLOBALS['config']['sess_lifetime'];
	$sess_name = $GLOBALS['config']['sess_prefix'] . '_SESSION';

	if((isset($session['_TS']) && count($session) == 1) || count($session) == 0){
		setcookie(
		$sess_name,
		'',
		$exp_time,
		$GLOBALS['config']['base_url'],
		$_SERVER['HTTP_HOST'],
		false,
		true
	);
	}else{
		$session['_TS'] = dechex($exp_time);
		$encoded_session = json_encode($session);
		$hashed = hash('sha256', $encoded_session . $GLOBALS['config']['secret']);
		$final_session_cookie_str = $hashed . '-' . $encoded_session;

		setcookie(
			$sess_name,
			$final_session_cookie_str,
			$exp_time,
			$GLOBALS['config']['base_url'],
			$_SERVER['HTTP_HOST'],
			false,
			true
		);
	}
}

/**
 * for getting possible value for enum
 */
function get_enum_values( $table, $field, $db)
{
	$type = $db->safe_query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->fetch_array(MYSQLI_ASSOC)['Type'];
	preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
	$enum = explode("','", $matches[1]);
	return $enum;
}

/**
 * HTML Generater for options
 */

/**
 * For select tag
 */
function html_select($options, $attrib)
{
	if($attrib !== null && count($attrib) !== 0){
		echo "<select ";
		foreach ($attrib as $key => $value) {
			echo "{$key}=\"{$value}\" ";
		}
		echo ">\n";
	}else{
		echo "<select>\n";
	}
	echo "<option value=\"\">-- select type --</option>\n";
	foreach ($options as $id => $text) {
		echo "<option value=\"{$id}\">{$text}</option>\n";
	}
	echo "</select>\n";
}

function html_table($body, $header, $class, $th_class, $tr_class, $td_class){
	if($class !== null){
		echo "<table class=\"{$class}\">\n";
	}else{
		echo "<table>\n";
	}
	
	if($header !== null){
		if($tr_class !== null){
			echo "<tr class=\"$tr_class\">";
		}else{
			echo "<tr>";
		}
		
		foreach ($header as $key => $value) {
			if($th_class !== null){
				echo "<th class=\"$tr_class\">";
			}else{
				echo "<th>";
			}
			echo $value;
			echo "</th>";
		}

		echo "</tr>\n";
	}

	foreach ($body as $bkey => $row) {
		if($tr_class !== null){
			echo "<tr class=\"$tr_class\">";
		}else{
			echo "<tr>";
		}
		
		foreach ($row as $key => $value) {
			if($td_class !== null){
				echo "<td class=\"$tr_class\">";
			}else{
				echo "<td>";
			}
			echo $value;
			echo "</th>";
		}

		echo "</tr>\n";
	}

	echo "</table>\n";
}

function html_ul($list, $attrib){
	if($attrib !== null && count($attrib) !== 0){
		echo "<ul ";
		foreach ($attrib as $key => $value) {
			echo "{$key}=\"{$value}\" ";
		}
		echo ">\n";
	}else{
		echo "<ul>\n";
	}

	foreach ($list as $key => $value) {
		echo "<li>";
		echo "$value";
		echo "</li>\n";
	}

	echo "</ul>\n";
}

?>