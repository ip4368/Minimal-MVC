<?php
/**
 * Author: Shing Sheung Daniel Ip
 * Purpose: part of the minimal php MVC framework for INFO20003
 *          config for the framework
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Set the logging level
 * 	'dev' : development
 *			This will log all the stuff like which controller is selected
 *  'prod': production
 *			This will only log errors
 */
$GLOBALS['config']['log_level'] = 'dev';

/**
 * This is the config of the base url
 * if the root of the website is serving at http://example.com/subfolder/
 * then the base_url should be set as /subfolder/
 */
$GLOBALS['config']['base_url'] = '/example/';

$GLOBALS['config']['index_page'] = '';

/**
 * SESSION Cookie prefix
 * and session encrypt secret, the secret should not be left blank
 * sess_lifetime is the length of the life of the session, in seconds
 */
$GLOBALS['config']['sess_prefix'] = 'EXAMPLE';
$GLOBALS['config']['secret'] = 'RBlLqYww8azSnO5O6WHkZoMKvhgsTigk23yaGrIs11K0WeQqwAoe9m8HE5d1Cxsl';
$GLOBALS['config']['sess_lifetime'] = 60 * 60 * 24 * 365;  // one year expiration

/**
 * Config for the db driver that the system is going to use
 */
$GLOBALS['config']['db_driver'] = 'mysqli';

// defualt mysqli config could be like this
$GLOBALS['config']['db_host'] = '127.0.0.1';
$GLOBALS['config']['db_user'] = 'root' ;
$GLOBALS['config']['db_passwd'] = ini_get("mysqli.default_pw");
$GLOBALS['config']['db_name'] = '';
$GLOBALS['config']['db_port'] = ini_get("mysqli.default_port");

?>