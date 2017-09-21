<?php
/**
 * Author: Shing Sheung Daniel Ip
 * Purpose: part of the minimal php MVC framework for INFO20003
 *          this file contains the base class of a controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class View{
	private $template;
	private $data;
	public function __construct($template){
		$template = ucfirst($template);
        $template_file_path = $GLOBALS['APP_PATH'] .
                           '/views/' .
                           strtolower($template) . '.php';
		$this->template = $template_file_path;
		$this->data = array();
	}

	public function render($data){
		extract($data);
		file_exists_fatal($this->template);
		include($this->template);
	}
}
?>