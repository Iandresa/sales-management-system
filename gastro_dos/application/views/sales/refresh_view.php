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
<div id="<?php echo show_scroll($cantidad_clientes) ?>">
    <div id="CollapsiblePanelGroup1" class="CollapsiblePanelGroup">
	<?php
	$num=0;
        $tempId = null;
	foreach( $lista_clientes->result() as $venta ){
            if($venta->sale_id != $tempId)
                $tempId = $venta->sale_id;
            else
                continue;
            $num++;
	?>
	<div class="CollapsiblePanel">
		<div class="CollapsiblePanelTab" tabindex="0">
                    <?php echo $num.' - '/*.$venta->postponeName*/;
                    $custumerName=$venta->first_name.' '.$venta->last_name;
                    if(strlen($custumerName)>1)
                    {
                        echo "<span class='cliente'>$custumerName</span>";
                    }
                    else //no hay cliente
                    {
                        echo "<span class='clienteVacio'>".$this->lang->line('sales_no_custumer')."</span>";
                    }
                    ?>  
                     
                    <span class="fecha"> <?php echo $venta->sale_time ?> </span>
                    <div id="r">
                        <span class="cant_a_pagar"><?php echo $this->lang->line('cafeteria_payment_amount')?></span> 
                        <span class="negro"> $<?php echo $venta->payment_type ?> </span> 
                    </div>
                    <?php if( ($venta->state == "postpone_order") || ($venta->state == "postpone_delivery") ) { ?>
                    <div style="text-align: right;">    
                        <span class="cant_a_pagar"><?php echo $this->lang->line('cafeteria_date_to_dispatch').': '?></span>
                        <span class="negro"> <?php echo $venta->sale_timeToFinish ?> </span>
                    </div>
                    <?php } ?>
                </div>
		<div class="CollapsiblePanelContent" >
		
		<!--<div id="comment"><?php echo ($venta->comment)? $venta->comment:'' ?></div>-->
		
			<table id="Cafeteria" border="1" cellpadding="0" cellspacing="2" >
			  <tr>
			    <th> <?php echo $this->lang->line('cafeteria_table_name'); ?> </th>
                            <th> <?php echo $this->lang->line('cafeteria_table_category'); ?> </th>
                            <th> <?php echo $this->lang->line('cafeteria_table_price'); ?> </th>
			    <th> <?php echo $this->lang->line('cafeteria_table_quantity'); ?> </th>
			    <th> <?php echo $this->lang->line('cafeteria_table_desc'); ?> </th>
			    <th> <?php echo $this->lang->line('cafeteria_table_total'); ?> </th>
			  </tr>
			  
			  <?php
                            $detalles = $this->Coffee->get_details_from_postpone_sales($venta->sale_id);
                            foreach( $detalles->result() AS $detalle ){
                          ?>     
			  <tr>
                            <td> <?php echo $detalle->name; ?> </td>
                            <td> <?php echo $detalle->category; ?> </td>
                            <td> <?php echo $detalle->item_unit_price; ?> </td>
                            <td> <?php echo $detalle->quantity_purchased; ?> </td>
                            <td> <?php echo $detalle->discount_percent; ?> </td>
                            <td> <?php 
				$money = ($detalle->item_unit_price*$detalle->quantity_purchased)-($detalle->item_unit_price*$detalle->quantity_purchased)*$detalle->discount_percent/100.00;
				echo  ($money==(int)$money)?$money.".00":$money ;//todo migue
				?> </td>  
                          </tr>
                          <?php
                          }
                          ?>
			</table>
									
			<div id="vinc">
                            <table style="width: 100%;">
                                <tr style="text-align: right;">                              
                                    <td>
                                        <?php 
                                            echo anchor('cafeteria/edit_sale/'.$venta->sale_id, "<div class='sb1'><span>".$this->lang->line('sales_edit')."</span></div>", array('title'=>$this->lang->line('sales_edit')));
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
			
		</div>
	</div>
	<?php	
	}
	?>
	
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