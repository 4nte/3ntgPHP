<?php
/**
* Multi language 
*/
class MultiLanguage
{

	private $language;
	private $translation;
	
	function __construct()
	{
		global $config;
		


		$this->language = isset($_SESSION['lang']) ? $_SESSION['lang'] : $config->get("default_language"); ;
		if(!$this->_load_translation()){
			die("failed to load".$this->language);
			$this->language = $config->get("default_language");
			$this->_load_translation();
		}
	}

	private function _load_translation(){
		$path = APP_DIR."languages/".$this->language.".php";

		if(!(file_exists($path) && is_readable($path)))
			return false;

		require($path);
		$this->translation = $t;
		return true;

	}
	public function getLanguage(){
		return $this->language;
	}
	public function getTranslation($key){

		return $this->translation[$key] ? $this->translation[$key] : $key;

	}

	public static function getLanguages(){
		$langs = array();
		
		$dir = new DirectoryIterator(APP_DIR."/languages");
		foreach ($dir as $fileinfo) {
			$status = 0;
			if (!$fileinfo->isDot()) {
				$name = str_replace(".php","",$fileinfo->getFilename());
				if(!isset($_SESSION["lang"]))
					$status = 0;
				else if($_SESSION["lang"] == $name)
					$status = 1;
				$langs[] = ["name" => $name, "status" => $status];
			}
		}
		return $langs;
	}

	
	
}