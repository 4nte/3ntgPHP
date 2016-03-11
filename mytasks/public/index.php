<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'Off');
ini_set("log_errors", 1);

// Define project root & application path
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/../');
define('APP_DIR', ROOT_DIR .'app/');

// Global MySql connection object
$conn; 
// Global configuration object
$config;

/*
* Load framework components:
*/

// MVC core
require_once ROOT_DIR."lib/Model.php";
require_once ROOT_DIR."lib/View.php";
require_once ROOT_DIR."lib/Controller.php";

// Mysqli connection
require_once ROOT_DIR."lib/DatabaseConnection.php";

// Logger
require_once ROOT_DIR."lib/Logger.php";

// Multi language
require_once ROOT_DIR."lib/MultiLanguage.php";

// Templating engine 
require_once ROOT_DIR."lib/Template.php";

// Global configuration 
require_once ROOT_DIR."lib/Config.php";

// Bootstrap function
require_once ROOT_DIR."lib/Bootstrap.php";

// Models
require_once APP_DIR."models/Tasks.php";


/*
* 3ntg framework entry function (Bootstrapping)
*/
EntgPHP();


 ?>