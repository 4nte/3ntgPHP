<?php
/**
* Logger
*/
class Logger
{

	private static function getClientIp(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    return$_SERVER['REMOTE_ADDR'];
		}
	}
	public static function log($type, $msg){
		$content;	
		if(strtolower($type) == "error") { $content = "[".date("Y.m.d H:i:s", time())."] Client from ".self::getClientIp()." >> ".$msg."\n"; }

		fwrite(fopen(ROOT_DIR.'error.log', 'a'), $content."<br>".PHP_EOL);
	}
	public static function read(){
		$content = file_get_contents(ROOT_DIR.'error.log', NULL, NULL, NULL, 8192);
		return strlen($content) > 0 ? $content : "--- empty ---";
	}
}