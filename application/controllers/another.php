<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Another extends Controller{
	public function front_page(){
		$this->load_model('Main');
		$model = new MainModel();
		
		//$this->assign('test', $GLOBALS['SESSION']['test']);
		$name = isset($this->params['name']) ? $this->params['name'] : "World";
        $name .= " in Another";
		$this->assign('greeting', $model->sayHello($name));
		$this->render_view('main');
	}
}
?>