<?php
/**
* Controller
*/
class Controller
{
	public $view;
	private $conn;

	function __construct(){

		$this->view = new View();
		$this->view->set("_languages", MultiLanguage::getLanguages());
	}

	public function setView($view){
		$this->view->view = $view;
	}

	public function redirect($destination){
		global $config;
		header('Location: '.$config->get('base_url').$destination);
	}
}