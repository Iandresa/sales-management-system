<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>

<?php
echo form_open('sale/update_sale_item/'.$sale_id.'/'.$sale_item_info->item_id,array('id'=>'sale_item_form'));
?>
<fieldset id="item_basic_info">
<legend><?php echo $this->lang->line("items_basic_information"); ?></legend>

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('cafeteria_table_quantity').':', 'quantity',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'quantity',
		'id'=>'quantity',
		'value'=>$sale_item_info->quantity_purchased)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('cafeteria_table_desc').':', 'discount_percent',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'discount_percent',
		'id'=>'discount_percent',
		'value'=>$sale_item_info->discount_percent)
	);?>
	</div>
</div>

<?php
echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>$this->lang->line('common_submit'),
	'class'=>'submit_button float_right')
);
?>
</fieldset>
<?php
echo form_close();
?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$('#item_form').validate({
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			quantity:
			{
				required:true,
				number:true
			},
			discount_percent:
			{
				required:true,
				number:true
			}
   		},
		messages:
		{
			quantity:
			{
				required:"<?php echo $this->lang->line('items_quantity_required'); ?>",
				number:"<?php echo $this->lang->line('items_quantity_number'); ?>"
			},
			discount_percent:
			{
				required:"<?php echo $this->lang->line('items_reorder_level_required'); ?>",
				number:"<?php echo $this->lang->line('items_reorder_level_number'); ?>"
			}

		}
	});
});
 
</script>