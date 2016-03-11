<?php

/*
* @Author Ante Gulin - 3ntgPHP
*/


function EntgPHP(){


global $conn, $config;
// Initialize components
$config = new Config();
$conn = new DatabaseConnection($config);

$request = [
	 "url" => null
	,"segmentedUrl" => null
	,"scriptUrl" => null
	,"controller" => null
    ,"view" => null
	,"action" => null
	,"params" => []
];

// Fetch request & script path
$request["url"] = $_SERVER['REQUEST_URI'];
$request["scriptUrl"] = $_SERVER['PHP_SELF'];

// If request specifies a controller

	// Slice request path in an array (controller/action)
	$request["segmentedUrl"] = explode("/", substr_replace($request["url"], "",0,1));
	

	// Get controller
	$request["controller"] = (isset($request["segmentedUrl"][0]) && strlen($request["segmentedUrl"][0]) > 0) ?  $request["segmentedUrl"][0] : $config->get("defaultController"); 
	
	// Get action
	$request["action"] = (isset($request["segmentedUrl"][1]) && strlen($request["segmentedUrl"][1]) > 0) ? $request["segmentedUrl"][1] : $config->get("defaultAction"); 

	// Get parameters
	for ($i=2; $i < sizeof($request["segmentedUrl"]); $i++) { 
		$request["params"][] = $request["segmentedUrl"][$i];
	}

	$controller;
	$controllerPath = APP_DIR."controllers/".$request["controller"]."Controller.php";
	if(file_exists($controllerPath)){

		//var_dump($request["action"]);
		require_once($controllerPath);
		if (!class_exists($request["controller"]."Controller")) 
			die("Class <strong>".$request["controller"]."Controller</strong> was not found ".$controllerPath);
		else if(!method_exists($request["controller"]."Controller", $request["action"]."Action"))
			die("<strong>".$request["action"]."Action </strong> not found at ".$request["controller"]."Controller");
		
		// Instantiate controller object
		$cname = $request["controller"]."Controller";
		$controller = new $cname;

		// Set default view layers
		$controller->view->template = $config->get("defaultTemplate");
		$controller->view->view = $request["controller"]."/".$request["action"];
		//die(var_dump($request["params"]));
		call_user_func_array(array($controller, $request["action"]."Action"),$request["params"] );
		$controller->view->renderView();
	}
	else{
		die("Controller not found at ".$controllerPath);
	}



}
