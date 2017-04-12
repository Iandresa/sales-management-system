<?php
function to_currency($number)
{
    $CI = & get_instance();
    $cur = $CI->Subsidary->get_currency($CI->session->userdata('subsidary_id'));
    $sym = $cur ? $cur->cur_symbol : "$";
    
	if($number >= 0)
	{
		return $sym.@number_format($number, 2, '.', '');
    }
    else
    {
    	return '-'.$sym.@number_format(abs($number), 2, '.', '');
    }
}


function to_currency_no_money($number)
{
	return @number_format($number, 2, '.', '');
}
?>