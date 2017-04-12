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
     
        
	<div class="CollapsiblePanel">
		
                <div class="CollapsiblePanelTab" tabindex="0"><?php echo $num.' - '.$venta->first_name.' '.$venta->last_name?> &nbsp;&nbsp; <span class="fecha"> <?php echo $venta->sale_time?> </span> 
                    <div id="r">
                        <span class="cant_a_pagar"><?php echo $this->lang->line('cafeteria_payment_amount').': '?></span> 
                        <span class="negro"> <?php echo $venta->payment_type ?> </span> 
                    </div>
                    <div style="text-align: right;">
                        <span class="cant_a_pagar"><?php echo $this->lang->line('cafeteria_date_to_dispatch').': '?></span>
                        <span class="negro"> <?php echo $venta->sale_timeToFinish ?> </span>
                    </div>
                </div>
		<div class="CollapsiblePanelContent" >
		
		<!--<div id="comment"><?php echo ($venta->comment)? $venta->comment:'' ?></div>-->
		
                        <div id="contentTable">
                        <?php 
                            echo form_open("cafeteria/finish_item_sale/$venta->sale_id", array('id' => 'finish_item_sale_form')); 
                        ?>
                        <table id="Cafeteria" border="1" cellpadding="0" cellspacing="2" >
                        <thead>  
			  <tr>
			    <th> <?php echo $this->lang->line('cafeteria_table_name'); ?> </th>
                            <th> <?php echo $this->lang->line('cafeteria_table_category'); ?> </th>
                            <th> <?php echo $this->lang->line('cafeteria_table_price'); ?> </th>
			    <th> <?php echo $this->lang->line('cafeteria_table_quantity'); ?> </th>
			    <th> <?php echo $this->lang->line('cafeteria_table_desc'); ?> </th>
			    <th> <?php echo $this->lang->line('cafeteria_table_total'); ?> </th>
                            <th></th>
                            <th> <input type="checkbox" class="selectall" /> </th>
			  </tr>
                        </thead>
                        <tbody>
			  <?php
                          foreach( $detalles->result() AS $detalle ){
                          ?>     
                      <tr>
    			<td> <?php echo $detalle->name; ?> </td>
                        <td> <?php echo $detalle->category; ?> </td>
                        <td> <?php echo $detalle->item_unit_price; ?> </td>
                        <td> <?php echo $detalle->quantity_purchased; ?> </td>
                        <td> <?php echo $detalle->discount_percent; ?> </td>
                        <td> <?php 
				$money = ($detalle->unit_price*$detalle->quantity_purchased)-($detalle->unit_price*$detalle->quantity_purchased)*$detalle->discount_percent/100.00;					
				echo  ($money==(int)$money)?$money.".00":$money ;//todo migue
				?> </td>
                        <td>
                            <?php
                                if($language=="english")
                                {
                                    echo anchor("cafeteria/cancel_item_sale/$venta->sale_id/$detalle->item_id",
                                    "<img border='0' src='images/closeicon.png'>",
                                    array('title'=>$this->lang->line('register_cancel'),'onclick'=>"return confirm('Are you sure you want to cancel this items?')"));
                                }
                                else
                                {
                                    echo anchor("cafeteria/cancel_item_sale/$venta->sale_id/$detalle->item_id",
                                    "<img border='0' src='images/closeicon.png'>",
                                    array('title'=>$this->lang->line('register_cancel'),'onclick'=>"return confirm('¿Esta seguro que desea cancelar este articulo?')"));
                                }
                            ?>
                        </td>
                        <td> 
                            <input type="checkbox" name="selected[]" class="selector" value="<?php echo $detalle->item_id; ?>"/>
                        </td>
                      </tr>
                      <?php
                      }
                      ?>     
                        </tbody>
			</table>
                        
                        <div id="finish_button" class="fButton" title="<?php echo $this->lang->line("recvs_complete_receiving"); ?>">
                            <span>
                                <?php echo $this->lang->line("recvs_complete_receiving"); ?>
                            </span>
                        </div>
                        </br>       
                        <div id="vinc"></div>
                        </form>
                        </div>	
		</div>
               
	</div>
       
        
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

    /*var jq = jQuery.noConflict();
    
    jq(document).ready(function(){ //esto no me pincha porque hay que ponerlo en el la vista original
        jq("#selectAll").click(function(){
            jq(':checkbox').attr('checked', this.checked);
        });                 
    });*/
    
    
    
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