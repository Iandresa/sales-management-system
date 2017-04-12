<?php if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

// Oscar

class Auxiliar 
{
	/**
 * return (bool)
 */
	function is_checked($text, $v)
	{
		$cant = strlen($text);
		for($i=0; $i<$cant; $i++)
		{
			if($text[$i]==$v)
			{
				return true;	
			}					
		}
		return false;
	}
        
        function base_Url_DMFormat()
	{            
		return str_ireplace(':','%3A',base_url());
	}
 }
?>