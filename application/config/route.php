<?php
/**
 * Author: Shing Sheung Daniel Ip
 * Purpose: part of the minimal php MVC framework for INFO20003
 *          this file contains all the route for the MVC
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// root of the site
$routes["/"] = "main.front_page";

//$routes["/:controller(user|admin)/:action/"] = ":controller.:action";
$routes["/another/"] = "another.front_page";

?>