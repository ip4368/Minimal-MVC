<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MainModel{
    public function sayHello($object){
        return sprintf("Hello %s!", $object);
    }
}

?>