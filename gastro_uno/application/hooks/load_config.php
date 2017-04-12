<?php
//Loads configuration from database into global CI config
function load_config()
{
	$CI =& get_instance();
	//change aki hay palo viene de appconfig
	
	foreach($CI->Appconfig->get_all() as $app_config)
	{
		$CI->config->set_item($app_config->key,$app_config->value);
	}
	
	if ($CI->config->item('language'))
	{
		$CI->lang->switch_to($CI->config->item('language'));
	}
	
	if ($CI->config->item('timezone'))
	{
		date_default_timezone_set($CI->config->item('timezone'));
	}	
}
?>