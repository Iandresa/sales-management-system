<?php

/*
  Gets the html table to manage people.
 */

function get_people_manage_table($people, $controller)
{
    $CI = & get_instance();
    $table = '<table class="tablesorter" id="sortable_table">';

//ECP V_2 punto 3.8.1
    $is_customer;
    foreach ($people->result() as $person)
    {
        if(isset($person->is_VIP)) $is_customer = true;
	else $is_customer = false;
    }
    
    if($is_customer)
    {    
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('common_VIP'),
            $CI->lang->line('common_last_name'),
            $CI->lang->line('common_first_name'),
            $CI->lang->line('common_email'),
            $CI->lang->line('common_phone_number'),
            //'&nbsp'
            $CI->lang->line('common_actions'),
        );
    }
    else //ECP V_2 punto 3.8.1
    {
        $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('common_last_name'),
        $CI->lang->line('common_first_name'),
        $CI->lang->line('common_email'),
        $CI->lang->line('common_phone_number'),
        //'&nbsp'
        $CI->lang->line('common_actions'),
    );
    }//ECP V_2 punto 3.8.1
    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_people_manage_table_data_rows($people, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the people.
 */

function get_people_manage_table_data_rows($people, $controller)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($people->result() as $person)
    {
        $table_data_rows.=get_person_data_row($person, $controller);
    }

    if ($people->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='6'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('common_no_data_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_person_data_row($person, $controller)
{
    $CI = & get_instance();
    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();
    
   
    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' id='person_$person->person_id' value='" . $person->person_id . "'/></td>";
    if(isset($person->is_VIP))//ECP V_2 punto 3.8.1
    {
        $a = ($person->is_VIP == 1)?'checked':'unchecked';
        $table_data_row.="<td width='5%'><input type='checkbox' id='person_$person->person_id' value='" . $person->person_id . "'   $a   disabled /></td>";
    }   
    
    $table_data_row.='<td width="20%">' . character_limiter($person->last_name, 13) . '</td>';
    $table_data_row.='<td width="20%">' . character_limiter($person->first_name, 13) . '</td>';
    $table_data_row.='<td width="30%">' . mailto($person->email, character_limiter($person->email, 22)) . '</td>';
    $table_data_row.='<td width="20%">' . character_limiter($person->phone_number, 13) . '</td>';
    $table_data_row.='<td width="5%">' . anchor($controller_name . "/view/$person->person_id/width:$width", $CI->lang->line('common_edit'), array('class' => 'thickbox', 'title' => $CI->lang->line($controller_name . '_update'))) . '</td>';
    $table_data_row.='</tr>';

    return $table_data_row;
}

/*
  Gets the html table to manage suppliers.
 */

function get_supplier_manage_table($suppliers, $controller)
{
    $CI = & get_instance();
    $table = '<table class="tablesorter" id="sortable_table">';

    $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('suppliers_company_name'),
        $CI->lang->line('common_last_name'),
        $CI->lang->line('common_first_name'),
        $CI->lang->line('common_email'),
        $CI->lang->line('common_phone_number'),
        '&nbsp');

    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_supplier_manage_table_data_rows($suppliers, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the supplier.
 */

function get_supplier_manage_table_data_rows($suppliers, $controller)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($suppliers->result() as $supplier)
    {
        $table_data_rows.=get_supplier_data_row($supplier, $controller);
    }

    if ($suppliers->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='7'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('common_no_data_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_supplier_data_row($supplier, $controller)
{
    $CI = & get_instance();
    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' id='person_$supplier->person_id' value='" . $supplier->person_id . "'/></td>";
    $table_data_row.='<td width="17%">' . character_limiter($supplier->company_name, 13) . '</td>';
    $table_data_row.='<td width="17%">' . character_limiter($supplier->last_name, 13) . '</td>';
    $table_data_row.='<td width="17%">' . character_limiter($supplier->first_name, 13) . '</td>';
    $table_data_row.='<td width="22%">' . mailto($supplier->email, character_limiter($supplier->email, 22)) . '</td>';
    $table_data_row.='<td width="17%">' . character_limiter($supplier->phone_number, 13) . '</td>';
    $table_data_row.='<td width="5%">' . anchor($controller_name . "/view/$supplier->person_id/width:$width", $CI->lang->line('common_edit'), array('class' => 'thickbox', 'title' => $CI->lang->line($controller_name . '_update'))) . '</td>';
    $table_data_row.='</tr>';

    return $table_data_row;
}

/*
  Gets the html table to manage items.
 */

function get_items_manage_table($items, $controller)
{
    $CI = & get_instance();
    $table = '<table class="tablesorter" id="sortable_table">';

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
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_items_manage_table_data_rows($items, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the items.
 */

function get_items_manage_table_data_rows($items, $controller)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($items->result() as $item)
    {
        $table_data_rows.=get_item_data_row($item, $controller);
    }

    if ($items->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('items_no_items_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_item_data_row($item, $controller)
{
    $CI = & get_instance();
    $item_tax_info = $CI->Item_taxes->get_info($item->item_id);
    $tax_percents = '';
    foreach ($item_tax_info as $tax_info)
    {
        if ($tax_info['percent'] != 0)
            $tax_percents.=$tax_info['percent'] . '%, ';
    }
    $tax_percents = substr($tax_percents, 0, -2);
    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();

    $quantity = 0;
    if($item->is_forSale)
        $quantity = number_format($item->quantity, 0);
    else
        $quantity = $item->quantity;

    $table_data_row = '<tr>';
    $table_data_row.="<td width='3%'><input type='checkbox' id='item_$item->item_id' value='" . $item->item_id . "'/></td>";
    $table_data_row.='<td width="15%">' . $item->item_number . '</td>';
    $table_data_row.='<td width="20%">' . $item->name . '</td>';
    $table_data_row.='<td width="14%">' . $item->category . '</td>';
    $table_data_row.='<td width="14%">' . to_currency($item->cost_price) . '</td>';
    $table_data_row.='<td width="14%">' . to_currency($item->unit_price) . '</td>';
    $table_data_row.='<td width="14%">' . $tax_percents . '</td>';
    $table_data_row.='<td width="14%">' . $quantity . '</td>';
    $table_data_row.='<td width="5%">' . anchor($controller_name . "/view/$item->item_id/width:$width", $CI->lang->line('common_edit'), array('class' => 'thickbox', 'title' => $CI->lang->line($controller_name . '_update'))) . '</td>';

    //Ramel Inventory Tracking
    $table_data_row.='<td width="10%">' . anchor($controller_name . "/inventory/$item->item_id/width:$width", $CI->lang->line('common_inv'), array('class' => 'thickbox', 'title' => $CI->lang->line($controller_name . '_count'))) . /* '</td>';//inventory count	
              $table_data_row.='<td width="5%">' */'&nbsp;&nbsp;&nbsp;&nbsp;' . anchor($controller_name . "/count_details/$item->item_id/width:$width", $CI->lang->line('common_det'), array('class' => 'thickbox', 'title' => $CI->lang->line($controller_name . '_details_count'))) . '</td>'; //inventory details	
    
    $table_data_row.='</tr>';
    return $table_data_row;
}

/*
  Gets the html table to manage giftcards.
 */

function get_giftcards_manage_table($giftcards, $controller)
{
    $CI = & get_instance();

    $table = '<table class="tablesorter" id="sortable_table">';

    $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('giftcards_giftcard_number'),
        $CI->lang->line('giftcards_card_value'),
        '&nbsp',
    );

    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_giftcards_manage_table_data_rows($giftcards, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the giftcard.
 */

function get_giftcards_manage_table_data_rows($giftcards, $controller)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($giftcards->result() as $giftcard)
    {
        $table_data_rows.=get_giftcard_data_row($giftcard, $controller);
    }

    if ($giftcards->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('giftcards_no_giftcards_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_giftcard_data_row($giftcard, $controller)
{
    $CI = & get_instance();
    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='3%'><input type='checkbox' id='giftcard_$giftcard->giftcard_id' value='" . $giftcard->giftcard_id . "'/></td>";
    $table_data_row.='<td width="15%">' . $giftcard->giftcard_number . '</td>';
    $table_data_row.='<td width="20%">' . to_currency($giftcard->value) . '</td>';
    $table_data_row.='<td width="5%">' . anchor($controller_name . "/view/$giftcard->giftcard_id/width:$width", $CI->lang->line('common_edit'), array('class' => 'thickbox', 'title' => $CI->lang->line($controller_name . '_update'))) . '</td>';

    $table_data_row.='</tr>';
    return $table_data_row;
}

//-------------------------------------------
// Ariel COde for generate Subsidaries table
//-------------------------------------------



function get_subsidaries_manage_table($subsidaries, $controller)
{
    $CI = & get_instance();
    $table = '<table class="tablesorter" id="sortable_table">';
    //$table='<table class="tablesorter" id="">';

    $headers = array(
        $CI->lang->line('subsidaries_company'),
        $CI->lang->line('subsidaries_address'),
        $CI->lang->line('common_country'),
        $CI->lang->line('subsidaries_email'),
        $CI->lang->line('subsidaries_phone'),
        '&nbsp',
        $CI->lang->line('subsidaries_action'),
            //"Actions"
    );
    /* if($subsidaries->num_rows() > 1)
      $headers[]= $CI->lang->line('subsidaries_action'); */

    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_subsidaries_manage_table_data_rows($subsidaries, $controller, $subsidaries->num_rows() > 1);
    $table.='</tbody></table>';
    return $table;
}

function get_subsidaries_manage_table_data_rows($subsidaries, $controller, $haveActions=TRUE)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($subsidaries->result() as $subsidary)
    {
        $table_data_rows.=get_subsidary_data_row($subsidary, $controller, $haveActions);
    }

    if ($subsidaries->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('subsidaries_no_subsidaries_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_subsidary_data_row($subsidary, $controller, $haveActions=TRUE)//subsidaria
{
    $CI = & get_instance();

    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();
    $style = "";

    if ($subsidary->subsidary_id == $CI->session->userdata('subsidary_id'))
    {
        $style = 'style="font-weight:bold"';
    }
    elseif ($CI->Subsidary->IsDeleted($subsidary->subsidary_id))
        $style .= 'style="font-style:italic;text-decoration: line-through"';

    $table_data_row = '<tr>';
    $table_data_row.='<td ' . $style . ' width="25%">' . $subsidary->company . '</td>';
    $table_data_row.='<td ' . $style . ' width="20%">' . $subsidary->address . '</td>';
    $table_data_row.='<td ' . $style . ' width="20%">' . $CI->Country->get_country($subsidary->country) . '</td>';
    $table_data_row.='<td ' . $style . ' width="20%">' . mailto($subsidary->email, character_limiter($subsidary->email, 22)) . '</td>';
    $table_data_row.='<td ' . $style . ' width="20%">' . $subsidary->phone . '</td>';

    $table_data_row.='<td width="12%" align="left" colspan="2" style="font-weight:normal">' . anchor($controller_name . "/view/$subsidary->subsidary_id/width:$width", $CI->lang->line('common_edit'), array('class' => 'thickbox', 'title' => $CI->lang->line($controller_name . '_update')));

    if (($subsidary->subsidary_id != $CI->session->userdata('subsidary_id')) && !($CI->Subsidary->IsDeleted($subsidary->subsidary_id)))
        $table_data_row.='&nbsp;&nbsp;&nbsp;&nbsp;' . anchor($controller_name . "/select/$subsidary->subsidary_id/width:$width", $CI->lang->line('common_select'), array('title' => $CI->lang->line($controller_name . '_select')));

    if ($subsidary->subsidary_id != $CI->session->userdata('subsidary_id')) //no sale el eliminar de la que esta activada
    {
        if ($CI->Subsidary->IsDeleted($subsidary->subsidary_id))//esta marcada como borrada la susidaria actual
        {
            //recover
            $table_data_row.='&nbsp;&nbsp;&nbsp;&nbsp;' . anchor($controller_name . "/UNdelete_subsidary/$subsidary->subsidary_id/width:$width", "<span style=color:green>" . $CI->lang->line('common_recover') . "</span>", array('title' => $CI->lang->line($controller_name . '_recover')));
        }
        else
        {
            //delete
            $table_data_row.='&nbsp;&nbsp;&nbsp;&nbsp;' . anchor($controller_name . "/delete_subsidary/$subsidary->subsidary_id/width:$width", "<span style=color:#666666;>" . $CI->lang->line('common_delete') . "</span>", array('title' => $CI->lang->line($controller_name . '_delete')));
        }
    }

    $table_data_row.='</td>';
    $table_data_row.='</tr>';
    return $table_data_row;
}

//===================================================================================
// Ariel COde for generate ENterprises table
//===================================================================================


function get_enterprises_manage_table($enterprises, $controller, $show_deleted=false)
{
    $CI = & get_instance();
    $table = '<table class="tablesorter" id="sortable_table">';

    $headers = array("&nbsp;",
        $CI->lang->line('enterprises_company'),
        $CI->lang->line('enterprises_address'),
        $CI->lang->line('enterprises_email'),
        $CI->lang->line('enterprises_phone'),
        '&nbsp',
        $CI->lang->line('enterprises_action'),
            //"Actions"
    );


    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_enterprises_manage_table_data_rows($enterprises, $controller, $show_deleted);
    $table.='</tbody></table>';
    return $table;
}

function get_enterprises_manage_table_data_rows($enterprises, $controller, $show_deleted=false)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($enterprises->result() as $enterprise)
    {
        $table_data_rows_current = "";
        $table_data_rows_current.=get_enterprise_data_row($enterprise, $controller);

        $subs = $CI->Enterprise->get_all_subsidaries_from_enterprise($enterprise->enterprise_id);

        $empty_enterprise = true;
        foreach ($subs->result() as $sub)
        {
            $sub_data_row_current = get_subsidary_data_row7C($sub, $controller, $show_deleted);
            if ($sub_data_row_current != "")
                $empty_enterprise = false;
            $table_data_rows_current.=$sub_data_row_current;
        }
        if (!$empty_enterprise)
            $table_data_rows.=$table_data_rows_current;
    }

    if ($enterprises->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('$enterprises_no_enterprises_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_subsidary_data_row7C($subsidary, $controller, $show_deleted=false)
{//este es el q se llama desde empresa
    $CI = & get_instance();

    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();
    $style = "";

    $printLine = true;
    if ($subsidary->subsidary_id == $CI->session->userdata('subsidary_id'))
    {
        $style = 'style="font-weight:bold"';
    }
    elseif ($CI->Enterprise->IsDeleted_Subsidary($subsidary->subsidary_id))
    {
        $style .= 'style="font-style:italic;text-decoration: line-through"';
        if (!$show_deleted)
            $printLine = false;
    }

    $table_data_row = '<tr>';


    $table_data_row = '<td ' . $style . 'width="10%"></td>';
    $table_data_row.='<td ' . $style . ' width="20%">' . $subsidary->company . '</td>';
    $table_data_row.='<td ' . $style . ' width="20%">' . $subsidary->address . '</td>';
    $table_data_row.='<td ' . $style . ' width="20%">' . mailto($subsidary->email, character_limiter($subsidary->email, 22)) . '</td>';
    $table_data_row.='<td ' . $style . ' width="15%">' . $subsidary->phone . '</td>';


    $table_data_row.='<td colspan="2" width="12%">' . anchor($controller_name . "/view/$subsidary->subsidary_id/$subsidary->enterprise_id/width:$width", $CI->lang->line('common_edit'), array('class' => 'thickbox', 'title' => $CI->lang->line('subsidaries_update'))); //edit


    if (($subsidary->subsidary_id != $CI->session->userdata('subsidary_id')) && !($CI->Enterprise->IsDeleted_Subsidary($subsidary->subsidary_id)))//la actual no esta seleccionada
        $table_data_row.= '&nbsp;&nbsp;&nbsp;&nbsp;' . anchor($controller_name . "/select/$subsidary->subsidary_id/width:$width", $CI->lang->line('common_select'), array('title' => $CI->lang->line('subsidaries_select'))); //select



    if ($subsidary->subsidary_id != $CI->session->userdata('subsidary_id')) //no sale el eliminar de la que esta activada
    {
        if ($CI->Enterprise->IsDeleted_Subsidary($subsidary->subsidary_id))//esta marcada como borrada la susidaria actual
        {
            //recover
            $table_data_row.='&nbsp;&nbsp;&nbsp;&nbsp;' . anchor($controller_name . "/UNdelete_subsidary_of_enterprise/$subsidary->subsidary_id/width:$width", "<span style=color:green>" . $CI->lang->line('common_recover') . "</span>", array('title' => $CI->lang->line('subsidaries_recover')));
        }
        else
        {
            //delete
            $table_data_row.='&nbsp;&nbsp;&nbsp;&nbsp;' . anchor($controller_name . "/delete_subsidary_of_enterprise/$subsidary->subsidary_id/width:$width", "<span style=color:#666666;>" . $CI->lang->line('common_delete') . "</span>", array('title' => $CI->lang->line('subsidaries_delete')));
        }
    }

    if (!$printLine)
        return "";
    $table_data_row.='</td>';
    $table_data_row.='</tr>';
    return $table_data_row;
}

function get_enterprise_data_row($enterprise, $controller)
{
    $CI = & get_instance();

    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();

    $table_data_row = '<tr >';
    //nombre empresa	
    $table_data_row.='<td colspan="5" style="font-weight:bold;color:#4386A1;">' . $enterprise->name . '</td>'; //edit
    $table_data_row.='<td >' . anchor($controller_name . "/createEnterprise/$enterprise->enterprise_id/width:$width", $CI->lang->line('common_edit'), array('class' => 'thickbox', 'title' => $CI->lang->line('enterprises_update'))) . '</td>';

    if ($CI->Enterprise->is_empty($enterprise->enterprise_id) || $CI->Enterprise->is_selected_and_the_only($enterprise->enterprise_id))
        $table_data_row.='<td></td>';
    else
        $table_data_row.='<td align="right">' . anchor($controller_name . "/deleteEnterprise/$enterprise->enterprise_id/width:400", "
<span class='deleteALL'>X</span>", array('title' => $CI->lang->line("delete_all_tooltip"))) . '</td>';

    $table_data_row.='</tr>';



    return $table_data_row;
}

//===================================================================================
// Ariel: Code for generate Campaings table
//===================================================================================

function get_campaign_manage_table($campaigns, $controller)
{
    $CI = & get_instance();
    $table = '<table class="tablesorter" id="sortable_table">';


    //

    $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('campaign_header_name'),
        $CI->lang->line('campaign_header_left'),
        $CI->lang->line('campaign_header_Type'),
        $CI->lang->line('campaign_header_displayposition'),
        "<span title='" . $CI->lang->line('campaign_header_ctrtooltip') . "'>" . $CI->lang->line('campaign_header_ctr') . '</span>',
        $CI->lang->line('common_actions'));


    $table.='<thead><tr>';
    $index = 0;
    foreach ($headers as $header)
    {
        if ($index++ == 4)
            $table.="<th>$header</th>";
        else
            $table.="<th><span>$header</span></th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_campaing_manage_table_data_rows($campaigns, $controller);
    $table.='</tbody></table>';
    return $table;
}

function get_campaing_manage_table_data_rows($campaigns, $controller)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($campaigns->result() as $person)
    {
        $table_data_rows.=get_campaign_data_row($person, $controller);
    }

    if ($campaigns->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='7'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('common_no_data_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_campaign_data_row($campaign, $controller)
{
    $CI = & get_instance();
    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' id='campaign_$campaign->campaign_id' value='" . $campaign->campaign_id . "'/></td>";
    $table_data_row.='<td width="28%">' . character_limiter($campaign->name, 20) . '</td>';

    if ($campaign->type == 'print')
        $table_data_row.='<td width="10%">' . $campaign->impresions_left . '/' . ($campaign->impresions_left + $campaign->impresions_count) . '</td>';
    else if ($campaign->type == 'click')
        $table_data_row.='<td width="10%">' . $campaign->clicks_left . '/' . ($campaign->clicks_left + $campaign->clicks_count) . '</td>';

    $table_data_row.='<td width="10%">' . $CI->lang->line('campaign_type_' . $campaign->type) . '</td>';
    $table_data_row.='<td width="10%">' . $CI->lang->line('campaign_position_articulo') . ' ' . $CI->lang->line('campaign_position_' . $campaign->position) . '</td>';

    $table_data_row.='<td width="10%">' . (($campaign->impresions_count != 0) ? (round($campaign->clicks_count / $campaign->impresions_count, 3)) : 0) . '</td>';

    $table_data_row.='<td width="5%">' . anchor($controller_name . "/view/$campaign->campaign_id/width:$width", $CI->lang->line('common_edit'), array('class' => 'thickbox', 'title' => $CI->lang->line($controller_name . '_update'))) . '</td>';
    $table_data_row.='</tr>';

    return $table_data_row;
}

//===================================================================================
// Ariel: Code for generate Campaings Offers table
//===================================================================================

function get_campaign_offer_manage_table($campaign_offers, $controller)
{
    $CI = & get_instance();
    $table = '<table class="tablesorter" id="sortable_table" border="0">';

    $headers = array('<input type="checkbox" id="select_all" />');
    $headers[] = $CI->lang->line('campaign_offer_header_DateCreated');
    $headers[] = $CI->lang->line('campaign_offer_header_name');
    $headers[] = $CI->lang->line('campaign_offer_header_type');
    $headers[] = $CI->lang->line('campaign_offer_header_price');
    $headers[] = $CI->lang->line('campaign_offer_header_usedTimes');
    $headers[] = $CI->lang->line('campaign_offer_header_DateExpire');
    //$headers[] = $CI->lang->line('common_actions');

    $table.='<thead><tr>';
    $index = 0;
    foreach ($headers as $header)
    {
        if ($index++ == 4)
            $table.="<th>$header</th>";
        else
            $table.="<th><span>$header</span></th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_campaing_offer_manage_table_data_rows($campaign_offers, $controller);
    $table.='</tbody></table>';
    return $table;
}

function get_campaing_offer_manage_table_data_rows($campaign_offers, $controller)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($campaign_offers->result() as $person)
    {
        $table_data_rows.=get_campaign_offer_data_row($person, $controller);
    }

    if ($campaign_offers->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='9'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('common_no_data_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_campaign_offer_data_row($campaign_offer, $controller)
{
    $CI = & get_instance();
    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' id='campaignoffer_$campaign_offer->campaign_offer_id' value='" . $campaign_offer->campaign_offer_id . "'/></td>";
    $table_data_row.='<td width="14%">' . date("j M Y", $campaign_offer->date_created) . '</td>';
    $table_data_row.='<td width="20%">' . $campaign_offer->name . '</td>';
    $table_data_row.='<td width="22%">' . $CI->Campaign_offer_model->get_offer_summary($campaign_offer) . '</td>';
    if (isset($campaign_offer->price))
        $table_data_row.='<td width="9%">' . '$ ' . number_format($campaign_offer->price, 2) . '</td>';
    else
        $table_data_row.='<td width="9%">' . '-' . '</td>';

    $table_data_row.='<td width="12%">' . $campaign_offer->used_times . '</td>';
    if (isset($campaign_offer->date_expire))
        $table_data_row.='<td width="12%">' . date("j M Y", $campaign_offer->date_expire) . '</td>';
    else
        $table_data_row.='<td width="12%">' . '--' . '</td>';

    //Actions
    //$table_data_row.= '<td width="5%">';
    //$table_data_row.= anchor("campaign_offer/view/$campaign_offer->campaign_offer_id"."/width:$width", $CI->lang->line('common_edit'),array('class'=>'thickbox none','title'=>$CI->lang->line($controller_name.'_update')));
    //$table_data_row.= '&nbsp;&nbsp;';
    //$table_data_row.= anchor("campaign_offer/delete/$campaign_offer->campaign_offer_id", $CI->lang->line('common_delete'));
    //$table_data_row.= '</td>';

    $table_data_row.='</tr>';

    return $table_data_row;
}

//===================================================================================
// Ariel: Code for generate Advisers table
//===================================================================================

function get_advisers_manage_table($advisers, $controller)
{
    $CI = & get_instance();

    //$CI->lang->switch_to("spanish");
    //$CI->lang->switch_to("english");
    // echo $CI->config->item('language');
    //$CI->config->set_item('language', 'spanish');     
    //$CI->lang->switch_to("english");

    $table = '<table class="tablesorter" id="sortable_table">';

    $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('common_last_name'),
        $CI->lang->line('common_first_name'),
        $CI->lang->line('common_email'),
        $CI->lang->line('common_actions'),
    );

    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_advisers_manage_table_data_rows($advisers, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the people.
 */

function get_advisers_manage_table_data_rows($advisers, $controller)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($advisers->result() as $adviser)
    {
        $table_data_rows.=get_adviser_data_row($adviser, $controller);
    }

    if ($advisers->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='5'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('common_no_data_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_adviser_data_row($adviser, $controller)
{
    $CI = & get_instance();
    $controller_name = $CI->uri->segment(1);
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' id='person_$adviser->person_id' value='" . $adviser->person_id . "'/></td>";
    $table_data_row.='<td width="15%">' . character_limiter($adviser->last_name, 13) . '</td>';
    $table_data_row.='<td width="15%">' . character_limiter($adviser->first_name, 13) . '</td>';
    $table_data_row.='<td width="20%">' . mailto($adviser->email, character_limiter($adviser->email, 22)) . '</td>';

    //actions
    $table_data_row.='<td width="10%">';

    $person_login = $CI->session->userdata('person_id');
    if (!$CI->Employee->is_AdviserUser($person_login))
    {
        if ($adviser->accepted_adviser == null)
        {
            $table_data_row.= anchor($controller_name . "/accept/$adviser->person_id", $CI->lang->line('adviser_accept'));
            $table_data_row.='&nbsp&nbsp';
            $table_data_row.= anchor($controller_name . "/deny/$adviser->person_id", $CI->lang->line('adviser_deny'));
        }
        else if ($adviser->accepted_adviser == 1)
        {
            $table_data_row.= anchor($controller_name . "/deny/$adviser->person_id", $CI->lang->line('adviser_deny'));
        }
        else if ($adviser->accepted_adviser == 0)
        {
            $table_data_row.= anchor($controller_name . "/accept/$adviser->person_id", $CI->lang->line('adviser_accept'));
        }
    }

    $table_data_row.= "&nbsp;&nbsp;";
    $table_data_row.= anchor($controller_name . "/view/$adviser->person_id/width:700", $CI->lang->line('common_edit'), array('class' => 'thickbox'));


    $table_data_row.='</td>';

    $table_data_row.='</tr>';

    return $table_data_row;
}

//===================================================================================
// Ariel: Code for generate Sales-Historical table
//===================================================================================

function get_saleshistorical_manage_table($sales)
{
    $CI = & get_instance();

    //$CI->lang->switch_to("spanish");
    //$CI->lang->switch_to("english");
    // echo $CI->config->item('language');
    //$CI->config->set_item('language', 'spanish');     
    //$CI->lang->switch_to("english");

    $table = '<table class="tablesorter" id="sortable_table">';

    $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('sales_date'),
        $CI->lang->line('sales_sale_for_customer'),
        $CI->lang->line('employees_employee'),
        $CI->lang->line('sales_comment'),
        $CI->lang->line('sales_payment'),
        $CI->lang->line('sales_mode'),
        $CI->lang->line(''),
    );

    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_saleshistorical_table_data_rows($sales);
    $table.='</tbody></table>';
    return $table;
}

function get_saleshistorical_table_data_rows($sales)
{
    $CI = & get_instance();
    $table_data_rows = '';

    if($sales)
    {
        foreach ($sales->result() as $sale)
        {
            $table_data_rows.=get_saleshistorical_data_row($sale);
        }
    }
    else
        $table_data_rows.="<tr><td colspan='8'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('common_no_data_to_display') . "</div></tr></tr>";

    return $table_data_rows;
}

function get_saleshistorical_data_row($sale)
{
    $CI = & get_instance();
    
    $customer = $CI->Person->get_info($sale->customer_id);
    $employee = $CI->Person->get_info($sale->employee_id);
    
    $controller_name = $CI->uri->segment(1);
    
    //HECTOR
    $mode = "";
    if($sale->mode == 'sale')
        $mode = $CI->lang->line('sales_sale');
    else
        $mode = $CI->lang->line('sales_return');
    //HECTOR
    
    $table_data_row = '<tr>';
    $table_data_row.="<td width='1%'><input type='checkbox' id='person_$sale->sale_id' value='" . $sale->sale_id . "'/></td>";
    $table_data_row.='<td width="14%">' . date("Y/m/d H:i", strtotime($sale->sale_time)) . '</td>';
    $table_data_row.='<td width="13%">' . character_limiter($customer->first_name ." ".$customer->last_name , 13) . '</td>';
    $table_data_row.='<td width="13%">' . character_limiter($employee->first_name ." ".$employee->last_name , 13) . '</td>';
    $table_data_row.='<td width="15%">' . character_limiter($sale->comment, 13) . '</td>';
    $table_data_row.='<td width="20%">' . $sale->payment_type . '</td>';
    $table_data_row.='<td width="7%">' . $mode . '</td>';

    //actions
    $table_data_row.='<td width="8%">';
        
    $table_data_row.= anchor($controller_name . "/load_historical_sale_bill/$sale->sale_id", $CI->lang->line('sales_printbill'), array('class' => 'thickbox'));
    
    $table_data_row.='</td>';

    $table_data_row.='</tr>';

    return $table_data_row;
}

//===================================================================================
// HL: Code for generate Sales-Historical Daily Cash table
//===================================================================================

function get_dailyCashHistorical_manage_table($sales)
{
    $CI = & get_instance();

    //$CI->lang->switch_to("spanish");
    //$CI->lang->switch_to("english");
    // echo $CI->config->item('language');
    //$CI->config->set_item('language', 'spanish');
    //$CI->lang->switch_to("english");

    $table = '<table class="tablesorter" id="sortable_table">';

    $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('sales_date'),
        $CI->lang->line('employees_employee'),
        $CI->lang->line('sales_total'),
        $CI->lang->line(''),
    );

    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_dailyCashHistorical_table_data_rows($sales);
    $table.='</tbody></table>';
    return $table;
}

function get_dailyCashHistorical_table_data_rows($sales)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($sales->result() as $sale)
    {
        $table_data_rows.=get_dailyCashHistorical_data_row($sale);
    }

    if ($sales->num_rows() == 0)
        $table_data_rows.="<tr><td colspan='5'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('common_no_data_to_display') . "</div></tr></tr>";

    return $table_data_rows;
}

function get_dailyCashHistorical_data_row($sale)
{
    $CI = & get_instance();

    $employee = $CI->Person->get_info($sale->employee_id);

    $controller_name = $CI->uri->segment(1);

    $table_data_row = '<tr>';
    $table_data_row.="<td width='1%'><input type='checkbox' id='person_$sale->id_dailyCash' value='" . $sale->id_dailyCash . "'/></td>";
    $table_data_row.='<td width="14%">' . date("Y/m/d H:i", strtotime($sale->date_time)) . '</td>';
    $table_data_row.='<td width="13%">' . character_limiter($employee->first_name ." ".$employee->last_name , 13) . '</td>';
    $table_data_row.='<td width="20%">' . $sale->total_amount . '</td>';

    //actions
    $table_data_row.='<td width="8%">';

    $table_data_row.= anchor($controller_name . "/load_historical_dailyCash_bill/$sale->id_dailyCash", $CI->lang->line('sales_printbill'), array('class' => 'thickbox'));

    $table_data_row.='</td>';

    $table_data_row.='</tr>';

    return $table_data_row;
}

//===================================================================================
// HL: Code for generate Sales-Historical Cycle Cash table
//===================================================================================

function get_cycleCashHistorical_manage_table($sales)
{
    $CI = & get_instance();

    //$CI->lang->switch_to("spanish");
    //$CI->lang->switch_to("english");
    // echo $CI->config->item('language');
    //$CI->config->set_item('language', 'spanish');
    //$CI->lang->switch_to("english");

    $table = '<table class="tablesorter" id="sortable_table">';

    $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('sales_date'),
        $CI->lang->line('employees_employee'),
        $CI->lang->line('sales_total'),
        $CI->lang->line(''),
    );

    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_cycleCashHistorical_table_data_rows($sales);
    $table.='</tbody></table>';
    return $table;
}

function get_cycleCashHistorical_table_data_rows($sales)
{
    $CI = & get_instance();
    $table_data_rows = '';

    if($sales)
    {
        foreach ($sales->result() as $sale)
        {
            $table_data_rows.=get_cycleCashHistorical_data_row($sale);
        }
    }
    else
        $table_data_rows.="<tr><td colspan='5'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('common_no_data_to_display') . "</div></tr></tr>";

    return $table_data_rows;
}

function get_cycleCashHistorical_data_row($sale)
{
    $CI = & get_instance();

    $employee = $CI->Person->get_info($sale->employee_id);

    $controller_name = $CI->uri->segment(1);

    $table_data_row = '<tr>';
    $table_data_row.="<td width='1%'><input type='checkbox' id='person_$sale->id_cycleCash' value='" . $sale->id_cycleCash . "'/></td>";
    $table_data_row.='<td width="14%">' . date("Y/m/d H:i", strtotime($sale->date_time)) . '</td>';
    $table_data_row.='<td width="13%">' . character_limiter($employee->first_name ." ".$employee->last_name , 13) . '</td>';
    $table_data_row.='<td width="20%">' . $sale->total_amount . '</td>';

    //actions
    $table_data_row.='<td width="8%">';

    $table_data_row.= anchor($controller_name . "/load_historical_cycleCash_bill/$sale->id_cycleCash", $CI->lang->line('sales_printbill'), array('class' => 'thickbox'));

    $table_data_row.='</td>';

    $table_data_row.='</tr>';

    return $table_data_row;
}

//===================================================================================
// Ariel: Code for generate Receivings-Historical table
//===================================================================================

function get_receivingshistorical_manage_table($receivings)
{
    $CI = & get_instance();

    //$CI->lang->switch_to("spanish");
    //$CI->lang->switch_to("english");
    // echo $CI->config->item('language');
    //$CI->config->set_item('language', 'spanish');     
    //$CI->lang->switch_to("english");

    $table = '<table class="tablesorter" id="sortable_table">';

    $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('sales_date'),
        $CI->lang->line('suppliers_supplier'),
        $CI->lang->line('employees_employee'),
        $CI->lang->line('sales_comment'),
        $CI->lang->line('sales_payment'),
        $CI->lang->line('sales_mode'),
        $CI->lang->line(''),
    );

    $table.='<thead><tr>';
    foreach ($headers as $header)
    {
        $table.="<th>$header</th>";
    }
    $table.='</tr></thead><tbody>';
    $table.=get_receivingshistorical_table_data_rows($receivings);
    $table.='</tbody></table>';
    return $table;
}

function get_receivingshistorical_table_data_rows($receivings)
{
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($receivings->result() as $rec)
    {
        $table_data_rows .= get_receivingshistorical_data_row($rec);
    }

    if ($receivings->num_rows() == 0)
    {
        $table_data_rows.="<tr><td colspan='6'><div class='warning_message' style='padding:7px;'>" . $CI->lang->line('common_no_data_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_receivingshistorical_data_row($receiving)
{
    $CI = & get_instance();
    
    $supplier = $CI->Person->get_info($receiving->supplier_id);
    $employee = $CI->Person->get_info($receiving->employee_id);
    
    
    $controller_name = $CI->uri->segment(1);
    
    //HECTOR
    $mode = "";
    if($receiving->mode == 'receive')
        $mode = $CI->lang->line('recvs_receiving');
    else
        $mode = $CI->lang->line('recvs_return');
    //
    
    $table_data_row = '<tr>';
    $table_data_row.="<td width='1%'><input type='checkbox' id='person_$receiving->receiving_id' value='" . $receiving->receiving_id . "'/></td>";
    $table_data_row.='<td width="14%">' . date("Y/m/d H:i", strtotime($receiving->receiving_time)) . '</td>';
    $table_data_row.='<td width="13%">' . character_limiter($supplier->first_name ." ".$supplier->last_name , 13) . '</td>';
    $table_data_row.='<td width="13%">' . character_limiter($employee->first_name ." ".$employee->last_name , 13) . '</td>';
    $table_data_row.='<td width="15%">' . character_limiter($receiving->comment, 13) . '</td>';
    $table_data_row.='<td width="20%">' . $receiving->payment_type . '</td>';
    $table_data_row.='<td width="15%">' . $mode . '</td>';

    //actions
    $table_data_row.='<td width="8%">';
        
    $table_data_row.= anchor($controller_name . "/load_historical_receiving_bill/$receiving->receiving_id", $CI->lang->line('sales_printbill'), array('class' => 'thickbox'));
    
    $table_data_row.='</td>';

    $table_data_row.='</tr>';

    return $table_data_row;
}




?>