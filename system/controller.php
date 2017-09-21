<?php
/**
 * Author: Shing Sheung Daniel Ip
 * Purpose: part of the minimal php MVC framework for INFO20003
 *          this file contains the base class of a controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Common controller class
 */
class Controller
{
    // db session for all the controller to use
    protected $db = null;

    // attribute for storing view data
    private $data;

    protected $params;

    function __construct()
    {
        // init the data for view as empty array
        $this->data = array();

        // need to init db session;
    }

    protected function load_model($model_name)
    {
        $model_name = ucfirst($model_name);
        $model_file_path = $GLOBALS['APP_PATH'] .
                           '/models/' .
                           strtolower($model_name) . '.php';
        safe_include($model_file_path);
    }

    protected function assign($variable, $value)
    {
        $this->data[$variable] = $value;
    }

    protected function render_view($view_name)
    {   
        // extract all the view data into normal scope, then run the view
        $view = new View($view_name);
        $view->render($this->data);
    }

    protected function connect_db()
    {
        if($this->db === null || $this->db->ping() === FALSE){
            $this->db = new Database($GLOBALS['config']['db_host']
                                   , $GLOBALS['config']['db_user']
                                   , $GLOBALS['config']['db_passwd']
                                   , $GLOBALS['config']['db_name']
                                   , $GLOBALS['config']['db_port']);
        }
    }

    public function set_params($params){
        $this->params = $params;
    }
}

?>