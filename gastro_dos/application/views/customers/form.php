<?php
echo form_open('customers/save/'.$person_info->person_id,array('id'=>'customer_form'));
?>
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="customer_basic_info">
<legend><?php echo $this->lang->line("customers_basic_information"); ?></legend>
<?php $this->load->view("people/form_basic_info"); ?>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('customers_account_number').':', 'account_number'); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'account_number',
		'id'=>'account_number',
		'value'=>$person_info->account_number)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('customers_VIP_limit').':', 'VIP_limit',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'VIP_limit',
		'id'=>'VIP_limit',
		'value'=>$person_info->VIP_limit)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('customers_taxable').':', 'taxable',array('class'=>'required wide','title'=>$this->lang->line('customers_taxable_tooltip'))); ?>
	<div class='form_field'>
	<?php echo form_checkbox('taxable', '1', $person_info->taxable == '' ? TRUE : (boolean)$person_info->taxable);?>
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
	$('#customer_form').validate({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_person_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
	          	zip: 
	            	{
                            required:true,
                            number:true
	            	},
	            	phone_number:"number",
			first_name: "required",
			last_name: "required",
			repeat_email:
			{
                            equalTo: "#email"
			},
	    		email:
	    		{
                            required:true,
                            email:true
	    		},
                        VIP_limit://ECP
                        {        
                            required:true,
                            number:true,
                            min:1
	    		},
                        address_1: "required",
                        city: "required",
                        state: "required"
   		},
		messages: 
		{
                  phone_number: "<?php echo $this->lang->line('common_valid_phonenumber'); ?>",
                    first_name: "<?php echo $this->lang->line('common_first_name_required'); ?>",
                    last_name: "<?php echo $this->lang->line('common_last_name_required'); ?>",
                    repeat_email:
                    {
                        equalTo: "<?php echo $this->lang->line('common_email_must_match'); ?>"
                    },
                    email:
                    {
                        required:"<?php echo $this->lang->line('config_email_required'); ?>",
                        email:"<?php echo $this->lang->line('common_email_invalid_format'); ?>"
                    },
                    zip: 
                    { 
                        required:"<?php echo $this->lang->line('config_zip_required'); ?>",
                        number:"<?php echo $this->lang->line('config_zip_number'); ?>"
                    },
                    address_1: "<?php echo $this->lang->line('common_address1_required'); ?>",
                    city: "<?php echo $this->lang->line('common_city_required'); ?>",
                    VIP_limit://ECP
                    {
                        required:"<?php echo $this->lang->line('customers_VIP_limit_required'); ?>", 
    			number:"<?php echo $this->lang->line('customers_VIP_limit_number'); ?>",
                        min:"<?php echo $this->lang->line('customers_VIP_limit_greater'); ?>"

                    },
                    state: "<?php echo $this->lang->line('common_state_required'); ?>"
		}
	});
});
</script>