<?php
/**
 * Author: Shing Sheung Daniel Ip
 * Purpose: part of the minimal php MVC framework for INFO20003
 *          this is the main script that control everything on the framework
 */

$GLOBALS['APP_PATH'] = dirname(__FILE__) . '/application';
$GLOBALS['SYS_PATH'] = dirname(__FILE__) . '/system';
$GLOBALS['config'] = array();

//error_reporting(0);

define('BASEPATH', true);

$GLOBALS['config'] = array();

require $GLOBALS['APP_PATH'] . "/config/config.php";

/**
 * Make sure the db driver php file exists and include the db driver
 */
$driver_file_path = $GLOBALS['SYS_PATH'] . '/database/' . $GLOBALS['config']['db_driver'] . '_driver.php';
if(!file_exists($driver_file_path)){
	echo 'database driver not found';
	exit;
}
include $driver_file_path;

$GLOBALS['config']['full_base_url'] = rtrim($GLOBALS['config']['base_url'].$GLOBALS['config']['index_page'], '/');

/**
 * check if the route is as expected in config file
 * if it fails, try to redirect to correct url
 */
if(strlen($_SERVER['REQUEST_URI']) == strlen($GLOBALS['config']['base_url']) &&
	strncmp($_SERVER['REQUEST_URI'], $GLOBALS['config']['base_url'], strlen($GLOBALS['config']['base_url'])) == 0 &&
	strcmp($GLOBALS['config']['index_page'], "") != 0){
	header('location: http://' . $_SERVER['HTTP_HOST'] . $GLOBALS['config']['full_base_url']);
	exit;
}

if(strncmp($_SERVER['REQUEST_URI'],
	$GLOBALS['config']['full_base_url'],
	strlen($GLOBALS['config']['full_base_url'])) != 0){
	$remaining_path = $GLOBALS['config']['full_base_url'];
	$tmpBase = rtrim($GLOBALS['config']['base_url']);
	$remaining_path = $remaining_path . substr($_SERVER['REQUEST_URI'], strlen($GLOBALS['config']['base_url']) - 1);
	header('location: http://' . $_SERVER['HTTP_HOST'] . $remaining_path);
	exit;
}

require $GLOBALS['SYS_PATH'] . "/helper/helper.php";
/**
 * Session handler
 */
$GLOBALS['SESSION'] = get_sess_cookie();
sess2cookie($GLOBALS['SESSION']);

include $GLOBALS['SYS_PATH'] . '/controller.php';
include $GLOBALS['SYS_PATH'] . '/view.php';

/*
 * parsing the path and parameters
 */
$path_exclude_base = substr($_SERVER['REQUEST_URI'], strlen($GLOBALS['config']['full_base_url']));
$pos = strpos($path_exclude_base, '?');
$curr_route = '';
$params = array();
if($pos === false){
	$curr_route = substr($_SERVER['REQUEST_URI'], strlen($GLOBALS['config']['full_base_url']));
}else{
	$curr_route = substr($_SERVER['REQUEST_URI'], strlen($GLOBALS['config']['full_base_url']), $pos);
	$params_str = substr($_SERVER['REQUEST_URI'], strlen($GLOBALS['config']['full_base_url']) + $pos + 1);
	parse_str($params_str, $params);
}

/*
 * load in all the routes and try to match them
 * prepare for the controller call
 */
$routes = array();
include $GLOBALS['SYS_PATH'] . '/router.php';
$matched = false;
$matched_router = NULL;
foreach ($routes as $r => $c) {
	$temp = new Router($r, $c);
	$matched = $temp->match($curr_route);
	if($matched !== false){
		$matched_router = $temp;
		break;
	}
}
if(!$matched){
	exit("not found<br>\n");
}

/* echo '<div class="hidden">'."\n";

echo 'matched with ' . $matched_router->get_str() . "<br>\n";
echo 'with following params<br>'."\n";
foreach ($matched_router->get_args() as $key => $value) {
	$params[$key] = $value;
}
foreach ($params as $key => $value) {
	echo htmlentities($key) . ' = ' . htmlentities($value) . "<br>\n";
}
echo 'using controller method ' . $matched_router->get_controller_method() . "<br>\n";

echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . "<br>\n";
echo "</div>\n"; */

$matched_router->inject_params($params);
$matched_router->run_method();

?>