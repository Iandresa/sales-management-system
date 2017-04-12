

<?php
function fechaLeer( $fecha )// yyyy-mm-dd => dd-mm-yyyy
{
		$cut = explode("-", $fecha);
		$a = $cut[0];
		$m = $cut[1];
		$d = $cut[2];	
	return $d."-".$m."-".$a;
}	
?>
<?php  
//$lista_clientes;
$cantidad_clientes = count($lista_clientes->result());
function show_scroll($cc)
{
	return ($cc>=20) ? 'scroll' : '';
}
?>
<?php
if( $cantidad_clientes > 0 ){
?>
<script type="text/javascript" language="javascript">

function printNice()
{
    var ficha = $('#print').html();
    var ventimp = window.open(' ', 'popimpr');
    ventimp.document.write( ficha );
    ventimp.document.close();
    ventimp.focus();
    ventimp.print();
    ventimp.close();   
}
function print2()
{
    var win=null;
    var ficha = $('#print').html();
     
    win = window.open();    
    self.focus();
    win.document.open();
    win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
    win.document.write('body, td { font-family: Verdana; font-size: 10pt;}');
    win.document.write('<'+'/'+'style'+'><'+'/'+'head'+'><'+'body'+'>');
    win.document.write('<input type="button" onClick="window.print()" value="<?php echo $this->lang->line('common_print_this');?>"/>');
    win.document.write('<p>'+ficha+'</p>');
    win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');
    win.document.close();
    win.print();
    win.close();
}
$(document).ready(function(){
        $('#printButton').click(function()
        {        
            var objBrowse = window.navigator;   
            if (objBrowse.appName == "Opera")                 
                 setTimeout('print2()', 2000);         
            else
                printNice();
        })     
});
</script> 
        <a id="printButton" title="<?php echo $this->lang->line('sales_print_report'); ?>"><img src="images/print.jpg" alt="<?php echo $this->lang->line('sales_print_report'); ?>" border="0"/></a>
    
        
<div id='print'>
    
    <div id="receipt_header" style="margin-left:40%; margin-right: 30%" >
         <img width="50px" style="azimuth: center;" src="<?php echo base_url().'/images/logotipo/'.$this->lang->line('login_out'); ?>.png"                  
                 border="0"/>
         
		<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<div id="company_address"><?php echo nl2br($this->config->item('address')); ?></div>
		<div id="company_phone"><?php echo $this->config->item('phone'); ?></div>
		<div id="sale_receipt"><?php echo $this->lang->line('common_list_of') . ' ' . $this->lang->line('module_' . $controller_name); ?></div>
		<div id="sale_time"><?php echo "$transaction_time" ?></div>
	</div>
	<div id="receipt_general_info" style="margin-left:50px">
		<?php if(isset($customer))
		{
		?>
			<div id="customer"><?php echo $this->lang->line('customers_customer').": ".$customer; ?></div>
		<?php
		}
		?>
		
	</div>
       
          <br/>
          
          
<div id="<?php echo show_scroll($cantidad_clientes) ?>">
    <div id="CollapsiblePanelGroup1" class="CollapsiblePanelGroup">
        <table id="receipt_items" style="margin-left:10px;
               border-collapse: collapse; 
                                        border-color:black;
                                        border: black solid 1px;
                                        text-align: center;">
            <header>   
                <tr>
                <th style="width: 5%;text-align:center; border: black solid 1px; color: black; font-family: sans-serif; font-size:14px; "><?php echo $this->lang->line('cafeteria_number_simbol'); ?></th>
                <th style="width:20%;text-align:center; border: black solid 1px;color: black; font-family: sans-serif; font-size:14px;"><?php echo $this->lang->line('cafeteria_customer'); ?></th>
                <th style="width:20%;text-align:center; border: black solid 1px;color: black; font-family: sans-serif; font-size:14px;"><?php echo $this->lang->line('cafeteria_order_date'); ?></th>
                <th style="width:25%;text-align:center; border: black solid 1px;color: black; font-family: sans-serif; font-size:14px;"><?php echo $this->lang->line('cafeteria_payment_amount'); ?></th>
                <th style="width:20%;text-align:center; border: black solid 1px;color: black; font-family: sans-serif; font-size:14px;"><?php echo $this->lang->line('cafeteria_date_to_dispatch'); ?></th>
                
                </tr>
            </header>
            <tbody>
	<?php
	$num=0; $temp=0;
        $tempId = null;
	foreach( $lista_clientes->result() as $venta ){
            if($venta->sale_id != $tempId)
                $tempId = $venta->sale_id;
            else
                continue;
            $num++;	
	?>
        <?php
            $detalles = $this->Coffee->get_details_from_unsettled_sale($venta->sale_id);

            if(count($detalles->result())>0){
        ?>
        
      
        
        
                <tr>
                    <th style="width:5%;text-align:center; text-align:center; border: black solid 1px; color: black; font-family: sans-serif; font-size:12px;"><?php echo $num ?></th>
                    <th style="width:20%;text-align:center;text-align:center; border: black solid 1px; color: black; font-family: sans-serif; font-size:12px;"><?php echo $venta->first_name.' '.$venta->last_name?></th>
                    <th style="width:20%;text-align:center;text-align:center; border: black solid 1px; color: black; font-family: sans-serif; font-size:12px;"><?php echo $venta->sale_time ?></th>
                    <th style="width:25%;text-align:center;text-align:center; border: black solid 1px; color: black; font-family: sans-serif; font-size:12px;"><?php echo $venta->payment_type ?></th>
                    <th style="width:20%;text-align:center;text-align:center; border: black solid 1px; color: black; font-family: sans-serif; font-size:12px;"><?php echo $venta->sale_timeToFinish ?></th>
                     

                </tr>
                
                <tr>
                    <td align="center" colspan="5" >
                        <table style="margin: 8px auto;
                                        width: 80%;
                                        border-collapse: collapse; 
                                        border-color:#777;
                                        border: #777 solid 1px;
                                        text-align: center;"
                                        id="Cafeteria688" border="1" cellpadding="0" cellspacing="2" >
                            <thead>  
                            <tr>
                                
                                <th style="width: 35%; font-family: sans-serif; font-size:11px; padding: .1em .1em; color: black;border: #777 solid 1px; "> <?php echo $this->lang->line('cafeteria_table_name'); ?> </th>
                                <th style="width: 20%; font-family: sans-serif; font-size:11px; padding: .1em .1em; color: black;border: #777 solid 1px; "> <?php echo $this->lang->line('cafeteria_table_category'); ?> </th>
                                <th style="width: 10%; font-family: sans-serif; font-size:11px; padding: .1em .1em; color: black;border: #777 solid 1px; "> <?php echo $this->lang->line('cafeteria_table_price'); ?> </th>
                                <th style="width: 10%; font-family: sans-serif; font-size:11px; padding: .1em .1em; color: black;border: #777 solid 1px; "> <?php echo $this->lang->line('cafeteria_table_quantity'); ?> </th>
                                <th style="width: 15%; font-family: sans-serif; font-size:11px; padding: .1em .1em; color: black;border: #777 solid 1px; "> <?php echo $this->lang->line('cafeteria_table_desc'); ?> </th>
                                <th style="width: 10%; font-family: sans-serif; font-size:11px; padding: .1em .1em; color: black;border: #777 solid 1px; "> <?php echo $this->lang->line('cafeteria_table_total'); ?> </th>
                            
			    </tr>
                        </thead>
                        <tbody>
			  <?php
                          foreach( $detalles->result() AS $detalle ){
                          ?>     
                            <tr style="font-family: sans-serif; font-size:10px;">
                                <td style="border: #777 solid 1px;"> <?php echo $detalle->name; ?> </td>
                                <td style="border: #777 solid 1px;"> <?php echo $detalle->category; ?> </td>
                                <td style="border: #777 solid 1px;"> <?php echo $detalle->item_unit_price; ?> </td>
                                <td style="border: #777 solid 1px;"> <?php echo $detalle->quantity_purchased; ?> </td>
                                <td style="border: #777 solid 1px;"> <?php echo $detalle->discount_percent; ?> </td>
                                <td style="border: #777 solid 1px;"> <?php 
				$money = ($detalle->unit_price*$detalle->quantity_purchased)-($detalle->unit_price*$detalle->quantity_purchased)*$detalle->discount_percent/100.00;					
				echo  ($money==(int)$money)?$money.".00":$money ;//todo migue
				?> </td>
                        
                      </tr>
                      <?php
                      }
                      ?>     
                        </tbody>
			</table>
                        
                    </td> 
                </tr>
        
                <tr><td align="center" colspan="5" ></td>                    
                </tr>
      
	<?php	
	}
        else
            $temp++;
        }
        if($num == $temp){?>
           <div id="cartel_sin_ventas_pendientes">
               <?php echo $this->lang->line('common_no_data_to_display'); ?>
           </div> 
        <?php }
	?>
                
                </tbody>
	</table>
</div>
</div>
</div>
<?php	
}
else{
?>
<div id="cartel_sin_ventas_pendientes">
<?php echo $this->lang->line('cafeteria_empty'); ?>
</div>	
<?php	
}
?>
    
 
<script language="JavaScript" type="text/javascript">
    var cpg = new Spry.Widget.CollapsiblePanelGroup("CollapsiblePanelGroup1");
</script>

<script language="JavaScript" type="text/javascript">		



    
    
    $(".selectall").click( function(){
        $(this).parents("#Cafeteria").find(":checkbox.selector").attr("checked", this.checked);
    });
    
    $(".fButton").click( function(){
        if( $(this).parents("#contentTable").find(":checkbox.selector:checked").length > 0 )
        {
            if( confirm('<?php echo $this->lang->line("cafeteria_confirm_finish_item"); ?>') )
                $(this).parents("#contentTable").find("#finish_item_sale_form").submit();
        }
        else
            alert("<?php echo $this->lang->line("cafeteria_none_selected"); ?>");
    });

</script>

