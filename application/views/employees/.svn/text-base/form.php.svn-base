<?php

if($this->uri->segment(1) == 'advisers')
    echo form_open('advisers/save/'.$person_info->person_id,array('id'=>'employee_form'));
else 
    echo form_open('employees/save/'.$person_info->person_id,array('id'=>'employee_form'));

?>
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="employee_basic_info">
    
    
<?php if($is_adviser_user == 1): ?>
    <legend><?php echo $this->lang->line("advisers_basic_info"); ?></legend>
<?php else :?>    
    <legend><?php echo $this->lang->line("employees_basic_information"); ?></legend>
<?php endif; ?>
    
    
<?php $this->load->view("people/form_basic_info"); ?>
</fieldset>

<fieldset id="employee_login_info">
<legend><?php echo $this->lang->line("employees_login_info"); ?></legend>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('employees_username').':', 'username',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'username',
		'id'=>'username',
		'value'=>$person_info->username));?>
	</div>
</div>

<?php
$password_label_attributes = $person_info->person_id == "" ? array('class'=>'required'):array();
?>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('employees_password').':', 'password',$password_label_attributes); ?>
	<div class='form_field'>
	<?php echo form_password(array(
		'name'=>'password',
		'id'=>'password'
	));?>
	</div>
</div>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('employees_repeat_password').':', 'repeat_password',$password_label_attributes); ?>
	<div class='form_field'>
	<?php echo form_password(array(
		'name'=>'repeat_password',
		'id'=>'repeat_password'
	));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('employees_secret_question').':', 'secret_question',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'secret_question',
		'id'=>'secret_question',
		'value'=>$person_info->secret_question));?>
	</div>
</div>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('employees_secret_answer').':', 'secret_answer',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'secret_answer',
		'id'=>'secret_answer',
		'value'=>$person_info->secret_answer));?>
	</div>
</div>



</fieldset>


<?php
if(!isset($is_adviser_user) || !$is_adviser_user)
{  
?>
<fieldset id="employee_permission_info">
<legend><?php echo $this->lang->line("employees_permission_info"); ?></legend>
<p><?php echo $this->lang->line("employees_permission_desc"); ?></p>

<ul id="permission_list">
    
    
<?php
  
    foreach($all_modules->result() as $module)
    {
        ?>
        <li>	
        <?php echo form_checkbox("permissions[]",$module->module_id,$this->Employee->has_permission($module->module_id,$person_info->person_id)); ?>
        <span class="medium"><?php echo $this->lang->line('module_'.$module->module_id);?>:</span>
        <span class="small"><?php echo $this->lang->line('module_'.$module->module_id.'_desc');?></span>
        </li>
        <?php
    }
?>
</ul>
<?php


?>
</fieldset>
<?php 
}

echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>$this->lang->line('common_submit'),
	'class'=>'submit_button float_right')
);

echo form_close();
?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$('#employee_form').validate({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_person_form_submit(response);
                                location.reload();
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			first_name: "required",
			last_name: "required",
			secret_question: "required",
			secret_answer: "required",
			username:
			{
                            required:true,
                            minlength: 5
			},
            		phone_number: {required:true, number:true},
			password:
			{
                            <?php
                            if($person_info->person_id == "")
                            {
                            ?>
                                required:true,
                            <?php
                            }
                            ?>
                            minlength: 8
			},	
			repeat_password:
			{
 				equalTo: "#password"
			},
			repeat_email:
			{
 				equalTo: "#email"
			},
                        email:
                        {
                            required:true,
                            email:true
                        },
                        zip: 
                        {
                            required:true,
                            number:true
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
                secret_question: "<?php echo $this->lang->line('employees_question_required'); ?>",
                secret_answer: "<?php echo $this->lang->line('employees_answer_required'); ?>",
     		username:
     		{
     			required: "<?php echo $this->lang->line('employees_username_required'); ?>",
     			minlength: "<?php echo $this->lang->line('employees_username_minlength'); ?>"
     		},
     		
                password:
                {
                        <?php
                        if($person_info->person_id == "")
                        {
                        ?>
                        required:"<?php echo $this->lang->line('employees_password_required'); ?>",
                        <?php
                        }
                        ?>
                        minlength: "<?php echo $this->lang->line('employees_password_minlength'); ?>"
                },
                repeat_password:
                {
                        equalTo: "<?php echo $this->lang->line('employees_password_must_match'); ?>"
     		},
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
                state: "<?php echo $this->lang->line('common_state_required'); ?>",
                phone_number: "<?php echo $this->lang->line('common_valid_phonenumber'); ?>"
		}
	});
});
</script>