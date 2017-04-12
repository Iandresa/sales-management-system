<script type="text/javascript" src="<?php echo base_url();?>js/calendar/js/jscal2.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/calendar/js/lang/en.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/calendar/css/jscal2.css" /> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/calendar/css/border-radius.css" />

<?php
echo form_open('campaign_offer/save/'.$offer_info['campaign_offer_id'],array('id'=>'campaign_offer_form'));
?>
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="campaign_offer_info" style="width: 330px">
<legend><?php echo $this->lang->line("campaign_offer_info"); ?></legend>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_offer_header_name').':', 'name',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'name',
		'id'=>'name',
		'value'=>$offer_info['name']));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_offer_header_type').':', 'type',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('type', $types, $offer_info['type'] , "id='type'");?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_offer_header_count').':', 'count',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'count',
		'id'=>'count',
		'value'=>$offer_info['count']));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_offer_header_price').':', 'price',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'price',
		'id'=>'price',
		'value'=>$offer_info['price']));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_offer_header_position').':', 'position',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('position', $positions, $offer_info['position'], "id='position'");?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_offer_header_DateExpire').':', 'date_expire',array('class'=>'field_row clearfix')); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name'=>'date_expire',
            'id'=>'date_expire',
            'value'=>($this->uri->segment(3)==-1) ? $offer_info['date_expire']:((!$offer_info['date_expire']) ? '':$this->fecha->timestamp_to_date($offer_info['date_expire'])) )); // change Oscar ?>
            <button type='button' id='datecreated_edit_trigger'>...</button>
             <script type='text/javascript'> 
                 Calendar.setup({
                     inputField     :    "date_expire",
                     dateFormat     :    "%d/%m/%Y",
                     showTime       :    false,
                     trigger        :    "datecreated_edit_trigger",
                     minuteStep     :    1,
                     onSelect       :    function() { this.hide() },
                     fdow           :    0
             });
             </script> 
	</div>
</div>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_offer_header_message').':', 'message'); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'message',
		'id'=>'message',
		'value'=>$offer_info['message'],
		'rows'=>'5',
		'cols'=>'17')		
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
	$('#campaign_offer_form').validate({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_campaign_offer_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			name: "required",
                        count: 
                        {
                            required:true,
                            number:true
                        },
			price:
                        {
                            required:true,
                            number:true
                        }
   		},
		messages: 
		{
                    name: "<?php echo $this->lang->line('common_name_required'); ?>",
                    count:
                    {
    			required:"<?php echo $this->lang->line('campaign_offer_count_required'); ?>",
    			number:"<?php echo $this->lang->line('campaign_offer_count_number'); ?>"
                    },
                    price:
                    {
    			required:"<?php echo $this->lang->line('campaign_offer_price_required'); ?>",
    			number:"<?php echo $this->lang->line('campaign_offer_price_number'); ?>"
                    }
		}
	});
});
</script>