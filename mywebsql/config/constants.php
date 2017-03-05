<?php
/**
 * This file is a part of MyWebSQL package
 * 
 * @file:      config/constants.php
 * @author     Samnan ur Rehman
 * @copyright  (c) 2008-2011 Samnan ur Rehman
 * @web        http://mywebsql.net
 * @license    http://mywebsql.net/license
 */

	// You should not change anything below unless you know what you are doing!
	define("EXTERNAL_PATH", str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]));
	
	define('APP_VERSION', '2.9');
	define('PROJECT_SITEURL', 'http://mywebsql.net');
	define("DEVELOPER_EMAIL", "support@mywebsql.net");
	define("COOKIE_LIFETIME", 1440);	// in hours

	// below is required to adjust for serverside php configuration changes
	ini_set("display_errors", "off");

	if (!function_exists('v'))
	{
		function v(&$check, $alternate = FALSE)
		{
			return (isset($check)) ? $check : $alternate;
		}
		
		function stripdata($data)
		{
			if (is_array($data))
			{
				foreach($data as $key => $value)
					$data[$key] = stripdata($value);
				return $data;
			}
			return stripslashes($data);
		}
		
		// this must be done only once, so it's here
		if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
		{
			foreach ($_REQUEST as $k=>$v)
				$_REQUEST[$k] = stripdata($v);
			foreach ($_POST as $k=>$v)
				$_POST[$k] = stripdata($v);
			foreach ($_GET as $k=>$v)
				$_GET[$k] = stripdata($v);
		}
		
		// this function is here because it is called very early (while functions.php is not included)
		function buffering_start() {
			function_exists('ob_gzhandler') && ( !ini_get( 'zlib.output_compression') )
				? ob_start("ob_gzhandler") : ob_start();
			ob_implicit_flush(0);
			// if a module cleans the buffer, then starts buffering again, this will avoid php notices
			if (!defined('OUTPUT_BUFFERING'))
				define('OUTPUT_BUFFERING', true);
		}
	}
?>