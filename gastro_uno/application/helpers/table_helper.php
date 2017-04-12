<?php
/*
Gets the html table to manage people.
*/
function get_people_manage_table($people,$controller)
{
	$CI =& get_instance();
	$table='<table class="tablesorter" id="sortable_table">';
	
	$headers = array('<input type="checkbox" id="select_all" />', 
	$CI->lang->line('common_last_name'),
	$CI->lang->line('common_first_name'),
	$CI->lang->line('common_email'),
	$CI->lang->line('common_phone_number'),
	//'&nbsp'
	$CI->lang->line('common_actions'),
	
	);
	
	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_people_manage_table_data_rows($people,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the people.
*/
function get_people_manage_table_data_rows($people,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($people->result() as $person)
	{
		$table_data_rows.=get_person_data_row($person,$controller);
	}
	
	if($people->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='6'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('common_no_persons_to_display')."</div></tr></tr>";
	}
	
	return $table_data_rows;
}

function get_person_data_row($person,$controller)
{
	$CI =& get_instance();
	$controller_name=$CI->uri->segment(1);
	$width = $controller->get_form_width();

	$table_data_row='<tr>';
	$table_data_row.="<td width='5%'><input type='checkbox' id='person_$person->person_id' value='".$person->person_id."'/></td>";
	$table_data_row.='<td width="20%">'.character_limiter($person->last_name,13).'</td>';
	$table_data_row.='<td width="20%">'.character_limiter($person->first_name,13).'</td>';
	$table_data_row.='<td width="30%">'.mailto($person->email,character_limiter($person->email,22)).'</td>';
	$table_data_row.='<td width="20%">'.character_limiter($person->phone_number,13).'</td>';		
	$table_data_row.='<td width="5%">'.anchor($controller_name."/view/$person->person_id/width:$width", $CI->lang->line('common_edit'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_update'))).'</td>';		
	$table_data_row.='</tr>';
	
	return $table_data_row;
}

/*
Gets the html table to manage suppliers.
*/
function get_supplier_manage_table($suppliers,$controller)
{
	$CI =& get_instance();
	$table='<table class="tablesorter" id="sortable_table">';
	
	$headers = array('<input type="checkbox" id="select_all" />',
	$CI->lang->line('suppliers_company_name'),
	$CI->lang->line('common_last_name'),
	$CI->lang->line('common_first_name'),
	$CI->lang->line('common_email'),
	$CI->lang->line('common_phone_number'),
	'&nbsp');
	
	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_supplier_manage_table_data_rows($suppliers,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the supplier.
*/
function get_supplier_manage_table_data_rows($suppliers,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($suppliers->result() as $supplier)
	{
		$table_data_rows.=get_supplier_data_row($supplier,$controller);
	}
	
	if($suppliers->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='7'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('common_no_persons_to_display')."</div></tr></tr>";
	}
	
	return $table_data_rows;
}

function get_supplier_data_row($supplier,$controller)
{
	$CI =& get_instance();
	$controller_name=$CI->uri->segment(1);
	$width = $controller->get_form_width();

	$table_data_row='<tr>';
	$table_data_row.="<td width='5%'><input type='checkbox' id='person_$supplier->person_id' value='".$supplier->person_id."'/></td>";
	$table_data_row.='<td width="17%">'.character_limiter($supplier->company_name,13).'</td>';
	$table_data_row.='<td width="17%">'.character_limiter($supplier->last_name,13).'</td>';
	$table_data_row.='<td width="17%">'.character_limiter($supplier->first_name,13).'</td>';
	$table_data_row.='<td width="22%">'.mailto($supplier->email,character_limiter($supplier->email,22)).'</td>';
	$table_data_row.='<td width="17%">'.character_limiter($supplier->phone_number,13).'</td>';		
	$table_data_row.='<td width="5%">'.anchor($controller_name."/view/$supplier->person_id/width:$width", $CI->lang->line('common_edit'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_update'))).'</td>';		
	$table_data_row.='</tr>';
	
	return $table_data_row;
}

/*
Gets the html table to manage items.
*/
function get_items_manage_table($items,$controller)
{
	$CI =& get_instance();
	$table='<table class="tablesorter" id="sortable_table">';
	
	$headers = array('<input type="checkbox" id="select_all" />', 
	$CI->lang->line('items_item_number'),
	$CI->lang->line('items_name'),
	$CI->lang->line('items_category'),
	$CI->lang->line('items_cost_price'),
	$CI->lang->line('items_unit_price'),
	$CI->lang->line('items_tax_percents'),
	$CI->lang->line('items_quantity'),
	'&nbsp', 
	$CI->lang->line('items_actions'),
	//'Inventory'//Ramel Inventory Tracking
	);
	
	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_items_manage_table_data_rows($items,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the items.
*/
function get_items_manage_table_data_rows($items,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($items->result() as $item)
	{
		$table_data_rows.=get_item_data_row($item,$controller);
	}
	
	if($items->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('items_no_items_to_display')."</div></tr></tr>";
	}
	
	return $table_data_rows;
}

function get_item_data_row($item,$controller)
{
	$CI =& get_instance();
	$item_tax_info=$CI->Item_taxes->get_info($item->item_id);
	$tax_percents = '';
	foreach($item_tax_info as $tax_info)
	{
		$tax_percents.=$tax_info['percent']. '%, ';
	}
	$tax_percents=substr($tax_percents, 0, -2);
	$controller_name=$CI->uri->segment(1);
	$width = $controller->get_form_width();

	$table_data_row='<tr>';
	$table_data_row.="<td width='3%'><input type='checkbox' id='item_$item->item_id' value='".$item->item_id."'/></td>";
	$table_data_row.='<td width="15%">'.$item->item_number.'</td>';
	$table_data_row.='<td width="20%">'.$item->name.'</td>';
	$table_data_row.='<td width="14%">'.$item->category.'</td>';
	$table_data_row.='<td width="14%">'.to_currency($item->cost_price).'</td>';
	$table_data_row.='<td width="14%">'.to_currency($item->unit_price).'</td>';
	$table_data_row.='<td width="14%">'.$tax_percents.'</td>';	
	$table_data_row.='<td width="14%">'.$item->quantity.'</td>';
	$table_data_row.='<td width="5%">'.anchor($controller_name."/view/$item->item_id/width:$width", $CI->lang->line('common_edit'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_update'))).'</td>';		
	
	//Ramel Inventory Tracking
	$table_data_row.='<td width="10%">'.anchor($controller_name."/inventory/$item->item_id/width:$width", $CI->lang->line('common_inv'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_count')))./*'</td>';//inventory count	
	$table_data_row.='<td width="5%">'*/'&nbsp;&nbsp;&nbsp;&nbsp;'.anchor($controller_name."/count_details/$item->item_id/width:$width", $CI->lang->line('common_det'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_details_count'))).'</td>';//inventory details	
	
	$table_data_row.='</tr>';
	return $table_data_row;
}

/*
Gets the html table to manage giftcards.
*/
function get_giftcards_manage_table( $giftcards, $controller )
{
	$CI =& get_instance();
	
	$table='<table class="tablesorter" id="sortable_table">';
	
	$headers = array('<input type="checkbox" id="select_all" />', 
	$CI->lang->line('giftcards_giftcard_number'),
	$CI->lang->line('giftcards_card_value'),
	'&nbsp', 
	);
	
	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_giftcards_manage_table_data_rows( $giftcards, $controller );
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the giftcard.
*/
function get_giftcards_manage_table_data_rows( $giftcards, $controller )
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($giftcards->result() as $giftcard)
	{
		$table_data_rows.=get_giftcard_data_row( $giftcard, $controller );
	}
	
	if($giftcards->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('giftcards_no_giftcards_to_display')."</div></tr></tr>";
	}
	
	return $table_data_rows;
}

function get_giftcard_data_row($giftcard,$controller)
{
	$CI =& get_instance();
	$controller_name=$CI->uri->segment(1);
	$width = $controller->get_form_width();

	$table_data_row='<tr>';
	$table_data_row.="<td width='3%'><input type='checkbox' id='giftcard_$giftcard->giftcard_id' value='".$giftcard->giftcard_id."'/></td>";
	$table_data_row.='<td width="15%">'.$giftcard->giftcard_number.'</td>';
	$table_data_row.='<td width="20%">'.to_currency($giftcard->value).'</td>';
	$table_data_row.='<td width="5%">'.anchor($controller_name."/view/$giftcard->giftcard_id/width:$width", $CI->lang->line('common_edit'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_update'))).'</td>';		
	
	$table_data_row.='</tr>';
	return $table_data_row;
}

//-------------------------------------------
// Ariel COde for generate Subsidaries table
//-------------------------------------------



function get_subsidaries_manage_table($subsidaries, $controller)
{
	$CI =& get_instance();
	//$table='<table class="tablesorter" id="sortable_table">';
	$table='<table class="tablesorter" id="">';
	
	$headers = array(
	$CI->lang->line('subsidaries_company'),
	$CI->lang->line('subsidaries_address'),
	$CI->lang->line('subsidaries_email'),
	$CI->lang->line('subsidaries_phone'),
	//$CI->lang->line('subsidaries_action')
    //"Actions"
	);
	if($subsidaries->num_rows() > 1)
		$headers[]= $CI->lang->line('subsidaries_action');
	
	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_subsidaries_manage_table_data_rows($subsidaries, $controller,$subsidaries->num_rows() > 1);
	$table.='</tbody></table>';
	return $table;
}

function get_subsidaries_manage_table_data_rows($subsidaries,$controller,$haveActions=TRUE)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($subsidaries->result() as $subsidary)
	{
		$table_data_rows.=get_subsidary_data_row($subsidary,$controller,$haveActions);
	}
	
	if($subsidaries->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('subsidaries_no_subsidaries_to_display')."</div></tr></tr>";
	}
	
	return $table_data_rows;
}

function get_subsidary_data_row($subsidary, $controller,$haveActions=TRUE)
{
	$CI =& get_instance();

	$controller_name=$CI->uri->segment(1);
	$width = $controller->get_form_width();
	
	if($subsidary->subsidary_id == $CI->session->userdata('subsidary_id'))
		$table_data_row='<tr style="font-weight:bold">';
	else
		$table_data_row='<tr>';
	$table_data_row.='<td width="25%">'.$subsidary->company.'</td>';
	$table_data_row.='<td width="20%">'.$subsidary->address.'</td>';
	$table_data_row.='<td width="20%">'.$subsidary->email.'</td>';
	$table_data_row.='<td width="20%">'.$subsidary->phone.'</td>';	
	


	if($subsidary->subsidary_id != $CI->session->userdata('subsidary_id'))
		$table_data_row.='<td width="12%" align="right" style="font-weight:normal">'.anchor($controller_name."/select/$subsidary->subsidary_id/width:$width", $CI->lang->line('common_select'),array('title'=>$CI->lang->line($controller_name.'_count')));
	else if ($haveActions)
	{
		$table_data_row.='<td></td>';		
	}


	//$table_data_row.='&nbsp;&nbsp;&nbsp;&nbsp;'.anchor($controller_name."/deactivate/$subsidary->subsidary_id/width:$width", $CI->lang->line('common_deactivate'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_count')));
	
	//$table_data_row.='&nbsp;&nbsp;&nbsp;&nbsp;'.anchor($controller_name."/delete_subsidary/$subsidary->subsidary_id/width:$width", $CI->lang->line('common_delete'),array('title'=>$CI->lang->line($controller_name.'_details_count'))).'</td>';//inventory details	

	
	$table_data_row.='</tr>';
	return $table_data_row;
}


//===================================================================================
// Ariel COde for generate ENterprises table
//===================================================================================


function get_enterprises_manage_table($enterprises, $controller)
{
	$CI =& get_instance();
	$table='<table class="tablesorter" id="sortable_table">';
	
	$headers = array("&nbsp;",
	$CI->lang->line('enterprises_company'),
	$CI->lang->line('enterprises_address'),
	$CI->lang->line('enterprises_email'),
	$CI->lang->line('enterprises_phone'),
	$CI->lang->line('enterprises_action'),
    //"Actions"
	);
	

	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_enterprises_manage_table_data_rows($enterprises, $controller);
	$table.='</tbody></table>';
	return $table;
}

function get_enterprises_manage_table_data_rows($enterprises, $controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($enterprises->result() as $enterprise)
	{
		$table_data_rows.=get_enterprise_data_row($enterprise, $controller);
		
		$subs = $CI->Enterprise->get_all_subsidaries_from_enterprise($enterprise->enterprise_id);
		foreach($subs->result() as $sub)
			$table_data_rows.=get_subsidary_data_row7C($sub, $controller);		
	}
	
	if($enterprises->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('$enterprises_no_enterprises_to_display')."</div></tr></tr>";
	}
	
	return $table_data_rows;
}

function get_subsidary_data_row7C($subsidary, $controller)
{
	$CI =& get_instance();

	$controller_name=$CI->uri->segment(1);
	$width = $controller->get_form_width();
	$style = "";
	
	if($subsidary->subsidary_id == $CI->session->userdata('subsidary_id'))
	{
		$style = 'style="font-weight:bold"';
	}
	elseif($CI->Enterprise->IsDeleted_Subsidary($subsidary->subsidary_id))
		//$style = 'style="font-weight:bold"';
		$style .= 'style="font-style:italic;text-decoration: line-through"';
		
		$table_data_row='<tr>';
	
	
	$table_data_row='<td '.$style.'width="10%"></td>';
	$table_data_row.='<td '.$style.' width="20%">'.$subsidary->company.'</td>';
	$table_data_row.='<td '.$style.' width="20%">'.$subsidary->address.'</td>';
	$table_data_row.='<td '.$style.' width="20%">'.$subsidary->email.'</td>';
	$table_data_row.='<td '.$style.' width="15%">'.$subsidary->phone.'</td>';	
	

	if($subsidary->subsidary_id != $CI->session->userdata('subsidary_id'))//la actual no esta seleccionada
		$table_data_row.='<td width="12%">'.anchor($controller_name."/select/$subsidary->subsidary_id/width:$width", $CI->lang->line('common_select'),array('title'=>$CI->lang->line($controller_name.'_count')));//select
	else
		$table_data_row.='<td width="12%"></td>';//vacio
	
	if($subsidary->subsidary_id != $CI->session->userdata('subsidary_id')) //no sale el eliminar de la que esta activada
	{
		if($CI->Enterprise->IsDeleted_Subsidary($subsidary->subsidary_id))//esta marcada como borrada la susidaria actual
		{					
				//recover
				$table_data_row.='&nbsp;&nbsp;&nbsp;&nbsp;'.anchor($controller_name."/UNdelete_subsidary_of_enterprise/$subsidary->subsidary_id/width:$width", "<span style=color:green>".'Recover'."</span>",array('title'=>$CI->lang->line($controller_name.'_details_count'))).'</td>';
		}
		else
		{		
				//delete
				$table_data_row.='&nbsp;&nbsp;&nbsp;&nbsp;'.anchor($controller_name."/delete_subsidary_of_enterprise/$subsidary->subsidary_id/width:$width", "<span style=color:#666666;>".$CI->lang->line('common_delete')."</span>",array('title'=>$CI->lang->line($controller_name.'_details_count'))).'</td>';//inventory details
			
		}
	}


	
			

	
	$table_data_row.='</tr>';
	return $table_data_row;
} 
function get_enterprise_data_row($enterprise, $controller)
{
	$CI =& get_instance();
	
	$controller_name=$CI->uri->segment(1);
	$width = $controller->get_form_width();
	
	$table_data_row='<tr>';
	
	$table_data_row.='<td colspan="5" style="font-weight:bold;color:#4386A1">'.$enterprise->name.'</td>';	
	if($CI->Enterprise->is_empty($enterprise->enterprise_id) || $CI->Enterprise->is_selected_and_the_only($enterprise->enterprise_id) )
		$table_data_row.='<td></td>';
	else
		$table_data_row.='<td align="right">'.anchor($controller_name."/deleteEnterprise/$enterprise->enterprise_id", "<span style=color:red;>".$CI->lang->line('common_delete')."</span>",array('title'=>$CI->lang->line($controller_name.'_count')) ).'</td>';
	
	
	$table_data_row.='</tr>';
	return $table_data_row;
}


?>