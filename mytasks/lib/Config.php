<?php
/**
* Config
*/
class Config 
{
	private $config;

	function __construct()
	{
		$this->config = parse_ini_file(APP_DIR."config/config.ini");
	}
	public function get($key){
			return $this->config[$key];
	}
	public function set($key, $value){
			$this->config[$key] = $value;
	}
}