<?php

/**
* View
*/
class View
{
	
	public $template;
	public $view;
	private $vars;
	private $templateEngine;


	public function __construct()
	{
		$this->templateEngine = new Template();
		$this->vars["_errors"] = [];
	}

	public function outputError($err){
		$this->vars["_errors"][] = $err;
	}
	public function set($key, $val) { $this->vars[$key] = $val; }
	public function get($key) { return $this->vars[$key]; }
	public function renderView(){
		$this->templateEngine->template_file = $this->template.".html";
		$this->templateEngine->view_file = $this->view.".html";
		$this->templateEngine->vars = $this->vars;
		echo $this->templateEngine->render();
	}
}