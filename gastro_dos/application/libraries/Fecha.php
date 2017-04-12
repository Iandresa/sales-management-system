<?php if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

// change Oscar

class Fecha 
{
	/**
 * return timestamp (int)
 */
	function date_to_timestamp($date)
	{   
		$day   = substr($date,0,2);
		$month = substr($date,3,2);
		$year  = substr($date,6,4);
		
		$hour   = date('h');
		$minute = date('i');
		$second = date('s'); 
		
		$timeStamp = mktime($hour, $minute, $second, $month, $day, $year);
		return $timeStamp;
	}
	
	/**
 * return (date)
 */
	function timestamp_to_date($timestamp)
	{
		$date = date("d/m/Y", $timestamp);
		return $date;
	}

}
?>