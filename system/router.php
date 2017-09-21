<?php
/**
 * Author: Shing Sheung Daniel Ip
 * Purpose: part of the minimal php MVC framework for INFO20003
 *          this file loads up the route.php and process all the routes
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require $APP_PATH . '/config/route.php';

class Router
{
	// attributes
	private $route;
	private $regex;
	private $controller;
	private $controller_template;
	private $controller_name;
	private $method_name;
	private $method;
	private $args_name = array();
	private $args = array();

	public function __construct($route, $controller){
		$this->route = $route;
		$this->controller_template = $controller;
		$this->route2regex();
	}

	private function route2regex(){
		$route_parts = explode('/', $this->route);
		$temp_new_route_parts = array();

		foreach ($route_parts as $part) {
			if(substr($part, 0, 1) == ':'){
				$exclude_colon = substr($part, 1);
				$pos = strpos($exclude_colon, '(');

				// non-choosing group, therefore no brackets
				if($pos == false){
					$name_tag = substr($part, 1);
					array_push($temp_new_route_parts, '(?P<' . $name_tag . '>[A-Za-z0-9-_]+)');

					// add to array of identifier
					array_push($this->args_name, $name_tag);
				}else{
					$name_tag = substr($exclude_colon, 0, $pos);
					$choosing_group = substr($exclude_colon, $pos);

					array_push($temp_new_route_parts, '(?P<' . $name_tag . '>' . $choosing_group . ')');

					// add to array of identifier
					array_push($this->args_name, $name_tag);
				}
			}else{
				array_push($temp_new_route_parts, $part);
			}
		}
		if(end($temp_new_route_parts) == '') array_pop($temp_new_route_parts);
		$this->regex = '/^' . implode('\/', $temp_new_route_parts) . '\/?$/';
	}

	private function match_controller(){
		// just link the global as needed
		global $APP_PATH;

		$controller_parts = explode('.', $this->controller_template);
		if(count($controller_parts) != 2){
			// controller template is not valid
			echo '500 internal server error<br>';
			exit();
		}
		if(substr($controller_parts[0], 0, 1) == ':'){
			$name = substr($controller_parts[0], 1);
			if(!array_key_exists($name, $this->args) or $this->args[$name] == ''){
				// controller not found
				echo '500 internal server error<br>';
				exit();
			}
			$this->controller_name = $this->args[$name];
		}else{
			// if not wildcard, just put it back to the new ctrl string
			$this->controller_name = $controller_parts[0];
		}
		$this->controller_name = ucfirst(strtolower($this->controller_name));
		$controller_file_path = $APP_PATH .
								'/controllers/' .
								strtolower($this->controller_name) . '.php';
		safe_include($controller_file_path);

		if(!class_exists($this->controller_name)){
			// controller not found
			echo '500 internal server error<br>';
			exit();
		}

		$this->controller = new $this->controller_name;

		if(substr($controller_parts[1], 0, 1) == ':'){
			$name = substr($controller_parts[1], 1);
			if(!array_key_exists($name, $this->args) or $this->args[$name] == ''){
				// method not found
				echo '500 internal server error<br>';
				exit();
			}
			$this->method_name = $this->args[$name];
		}else{
			// if not wildcard, just put it back to the new ctrl string
			$this->method_name = $controller_parts[1];
		}
		$this->method_name = strtolower($this->method_name);
		if(!method_exists($this->controller, $this->method_name)){
			// method not found
			echo '500 internal server error<br>';
			exit();
		}
	}

	public function match($route){
		$matches = array();
		$result = preg_match($this->regex, $route, $matches);
		if(!$result) return false;
		else{
			foreach ($this->args_name as $arg) {
				$this->args[$arg] = $matches[$arg];
			}
			$this->match_controller();
			return true;
		}
	}

	public function get_str(){
		return $this->route;
	}

	public function get_args(){
		return $this->args;
	}

	public function get_controller(){
		return $this->controller;
	}

	public function inject_params($params){
		$this->controller->set_params($params);
	}

	public function run_method(){
		return $this->controller->{$this->method_name}();
	}

	public function get_controller_method(){
		return $this->controller_name . '.' . $this->method_name;
	}
}
?>