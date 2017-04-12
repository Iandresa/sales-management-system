<?php 
    if($this->Module->get_allowed_module_sale())
{ ?>


<div style="border:solid 1px #A7A7A7;padding:10px">
<?php
function fecha( $fecha )// yyyy-mm-dd => dd-mm-yyyy
{
        $cut = explode("-", $fecha);
        $a = $cut[0];
        $m = $cut[1];
        $d = $cut[2];	
	return $d."-".$m."-".$a;
}	
?>
<?php  
//$lista_deliveries;
$cantidad_deliveries = count($deliveries_to_finish->result());
function scroll($cc)
{
	return ($cc>=20) ? 'scroll' : '';
}
?>
<?php
if( $cantidad_deliveries > 0 ){
?>
<div id="<?php echo scroll($cantidad_deliveries) ?>">
    <div id="CollapsiblePanelGroup2" class="CollapsiblePanelGroup">
	<?php
            $num=0; $temp=0;
            $tempId = null;
            foreach( $deliveries_to_finish->result() as $delivery ){
                if($delivery->sale_id != $tempId)
                    $tempId = $delivery->sale_id;
                else
                    continue;
            $num++;	
	?>
        <?php
            $detalles = $this->Delivery->get_details_from_unsettled_sale($delivery->sale_id);
            $finished_items = $this->Delivery->get_sale_items_finished($delivery->sale_id);
                    
            if(count($finished_items->result())>0){
        ?>
	<div class="CollapsiblePanel">
		<div class="CollapsiblePanelTab" tabindex="0"><?php echo $num.' - '.$delivery->first_name.' '.$delivery->last_name?> &nbsp;&nbsp; <span class="fecha"> <?php echo $delivery->sale_time?> </span> 
                    <div id="r">
                        <span class="cant_a_pagar"><?php echo $this->lang->line('cafeteria_payment_amount')?></span> 
                        <span class="negro"><?php echo $delivery->payment_type ?></span>
                    </div>
                    <div style="text-align: right;">
                        <span class="cant_a_pagar"><?php echo $this->lang->line('cafeteria_date_to_dispatch').': '?></span>
                        <span class="negro"> <?php echo $delivery->sale_timeToFinish ?> </span>
                    </div>
                </div>
		<div class="CollapsiblePanelContent" >
                    <div id="address" align="center">
                        <?php echo form_label($this->lang->line('common_address_1').':', 'address_1'); ?> <?php echo ($delivery->address_1)? $delivery->address_1:'' ?> &nbsp;&nbsp; <?php echo form_label($this->lang->line('common_city').':', 'city'); ?> <?php echo ($delivery->city)? $delivery->city:'' ?><br />
                        <?php echo form_label($this->lang->line('common_address_2').':', 'address_2'); ?> <?php echo ($delivery->address_2)? $delivery->address_2:'' ?> &nbsp;&nbsp; <?php echo form_label($this->lang->line('common_state').':', 'state'); ?> <?php echo ($delivery->state)? $delivery->state:'' ?>
                    </div>
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
			  </tr>
                        </thead>
                        <tbody>
			  <?php
                            foreach ($finished_items->result() AS $finished_item) {
                                ?>     
                                <tr style="background-color:#EEEEEE;">
                                    <td> <?php echo $finished_item->name; ?> </td>
                                    <td> <?php echo $finished_item->category; ?> </td>
                                    <td> <?php echo $finished_item->item_unit_price; ?> </td>
                                    <td> <?php echo $finished_item->quantity_purchased; ?> </td>
                                    <td> <?php echo $finished_item->discount_percent; ?> </td>
                                    <td> <?php
                                        $money = ($finished_item->unit_price * $finished_item->quantity_purchased) - ($finished_item->unit_price * $finished_item->quantity_purchased) * $finished_item->discount_percent / 100.00;
                                        echo ($money == (int) $money) ? $money . ".00" : $money; //todo migue
                                  ?></td>
                                    <td> 
                                        <img src="images/closed.png" />
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    
                    <div id="vinc">
                        <table style="width: 100%;">
                            <tr style="text-align: right;">                              
                                <td>
                                    <?php
                                        if(count($detalles->result())>0)
                                            echo "<a id='finishB' title='".$this->lang->line('cafeteria_finish_delivery')."'><div class='sb'><span>".$this->lang->line('cafeteria_finish_delivery')."</span></div></a>";    
                                        else
                                            echo anchor($controller_name.'/goToPostpone/'.$delivery->sale_id, "<div class='sb'><span>".$this->lang->line('cafeteria_finish_delivery')."</span></div>", array('title'=>$this->lang->line('cafeteria_finish_delivery')));
                                    ?>
                                </td>
                            </tr>
                        </table>
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
<?php echo $this->lang->line('common_no_data_to_display'); ?>
</div>	
<?php	
}
?>
</div>
<?php } ?>
<script language="JavaScript" type="text/javascript">
    var cpg = new Spry.Widget.CollapsiblePanelGroup("CollapsiblePanelGroup2");
</script>

<script language="JavaScript" type="text/javascript">
    $("#finishB").click(function()
    {
         alert("<?php echo $this->lang->line("sales_items_not_finished_delivery"); ?>");
    });
</script>