<?php
/**
* templating engine
*/

class Template
{
	
	// @string Paths to markup files
	public $template_file;
	public $view_file;

	// @array - Data defined at Controller
	public $vars;

	// @string - Loaded markup
	private $template;
	private $view;

	// @MultiLanguage object - language module
	private $lang;


	function __construct(){
		$this->lang = new MultiLanguage();

	
	}
	private function _load_view(){
		$viewFile = APP_DIR."views/".$this->view_file;
		if( file_exists($viewFile) && is_readable($viewFile) )
			$path = $viewFile;
		else
			die("Pogreška! view ($viewFile) nije pronađen.");
		$this->view = file_get_contents($path);

	}
	private function _load_template(){
		$templateFile = APP_DIR."templates/".$this->template_file;
		if( file_exists($templateFile) && is_readable($templateFile) )
			$path = $templateFile;
		else
			die("Pogreška! template ($templateFile) nije pronađen.");

		$this->template = file_get_contents($path);

	}

	private function evaluateSimpleExpression($exp, $extra = null){
			$flag_t = false;
			$exp = explode("|", $exp);
			
			// Check for translation filter
			if(isset($exp[1])){ $flag_t = $exp[1] == "t" ? true : false; }


			if(!is_null($extra)){
				$cmd = explode(".", $exp[0]);
				if(isset($cmd[1])){
					$exp = $this->vars[$extra["var"]][$extra["index"]][$cmd[1]];
					return $flag_t ?  $this->lang->getTranslation($exp) : $exp;
				}
				else if($exp[0] == $extra["alias"])
					return $this->vars[$extra["var"]][$extra["index"]];
				else{
					return $this->lang->getTranslation($exp[0]);
					die($flag_t ?  $this->lang->getTranslation($exp[0]) : $exp[0]);
				}

			}
			elseif($flag_t){
				return $this->lang->getTranslation($exp[0]);
			}
			elseif(isset($this->vars[$exp[0]])){ 
				return $this->vars[$exp[0]];
			}
			elseif($exp[0] == "view()")
				return $this->view;

			return Null;
	}
	private function evaluateLoopExpression($loop){


		$variable;
		$alias;
		$expected;
		foreach (explode(" ", $loop[0]) as $elem) {
			if($elem == "foreach")
				$expected = 1;
			else if($elem == "in")
				$expected = 2;
			else if($expected == 1)
				$alias = $elem;
			else if($expected == 2)
				$variable = $elem;
				
		}
		$evaluated ="";
		if(!isset($this->vars[$variable]))
			return "";
		for ($i=0; $i < count($this->vars[$variable]); $i++) { 

			$evaluated .= $this->evaluateExpressions($loop[1], array("var" => $variable, "alias" => $alias, "index" => $i));
			
		}
		return $evaluated;
	}

	private function evaluateExpressions($temp, $extra = null){

		$evaluated = null;
		$c_start = -1;
		$c_end = -1;
		$length = strlen($temp);
		$type = null;
		$step = 0;

		/*
		* Expression types
		* 	1: simple expressions
		*   2: loops
		*/


		for ($i=0; $i < $length ; $i++) {


			$c_char = substr($temp, $i,1);
			$n_char = substr($temp, $i+1,1);
			if( $c_end+1  > $c_start ){
				// Loop tag
				if( $c_char == "{" && $n_char == "*"){
			
					$c_start = $i;
					$type = 2;
					$loop = array(0 => null, 1 => null);
					$b_start = null;
					$b_end = null;

					
					for ($k=$i; $k < $length ; $k++) {
						$c_char = substr($temp, $k,1);
						$n_char = substr($temp, $k+1,1);
						if( !$loop[0] && ($c_char == "*" && $n_char == "}")) { 
							$loop[0] = substr($temp, $c_start+2, $k-$c_start-2);
							$b_start = $k+2;
							continue;
						}
						else if(!$loop[1] && ($c_char == "\\" && $n_char == "}")){
							$c_end = $k+1;

							$loop[1] = substr($temp, $b_start, $k - $b_start);
							
							$evaluated =$this->evaluateLoopExpression($loop);
							continue;
						}
					}
						

			
				}
				// Simple tag
				elseif($c_char == "{" && $n_char == "{"){
					$c_start = $i;
					$type = 1;
				}
			}
			else{
				if($type == 2 && $c_char == "*" && $n_char == "}"){
					$c_end = $i;
				}
				elseif($type == 1 && $c_char == "}" && $n_char == "}"){
					$c_end = $i;
					$evaluated = $this->evaluateSimpleExpression(substr($temp,$c_start+2, $c_end-$c_start-2),$extra);
				}
			}

		// No remaining expressions found in this template - return
		if($i == $length-1 && $c_end == -1)
			return $temp;
		// Expression found 
		else if($c_end > $c_start){
			$temp = substr_replace($temp,$evaluated, $c_start, 2+$c_end-$c_start);
			$length = strlen($temp);
			$i = 0;
			$c_start = -1;
			$c_end = -1;
			$type = null;
		}

		}
		return $temp;
	}
	public function render( $extra=array() ) {
		$this->_load_template(); 
		$this->_load_view(); 
		return $this->evaluateExpressions($this->template);
	} 
}
?>
