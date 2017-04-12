<?php $this->load->view("partial/header"); ?>
<br />

<?php
$person_id = $this->session->userdata('person_id');
if($this->Employee->is_AdviserUser($person_id))
{
    $adviser = $this->Adviser->get_adviser($person_id);
    $adviser = $adviser->row();
    if($adviser->accepted_adviser == null)
        echo '<h3>'.$this->lang->line('advisers_no_accepted_welcommessage').'</h3>';
    else
        echo '<h3>'.$this->lang->line('common_welcome_message').'</h3>';
}
else
        echo '<h3>'.$this->lang->line('common_welcome_message').'</h3>';
    
?>

<div id="home_module_list">
	<?php
	$is_adviser_user = $this->Employee->is_AdviserUser($this->session->userdata('person_id'));
   foreach($allowed_modules->result() as $module)
	{
	?>
	<div class="module_item">
		<a href="<?php echo site_url("$module->module_id");?>">
		<img src="<?php echo base_url().'images/menubar/'.$module->module_id.'.png';?>" border="0" alt="Menubar Image" /></a><br />

            <?php if($is_adviser_user == 1 && $module->module_id == "advisers"): ?>
                    <a href="<?php echo site_url("$module->module_id");?>"><?php echo $this->lang->line("module_".$module->module_id."_1") ?></a>
                     - <?php echo $this->lang->line('module_'.$module->module_id.'_desc_1');?>

            <?php else: ?>
               		<a href="<?php echo site_url("$module->module_id");?>"><?php echo $this->lang->line("module_".$module->module_id) ?></a>
                    - <?php echo $this->lang->line('module_'.$module->module_id.'_desc');?>
           <?php endif; ?>
        
	</div>
	<?php
	}
	?>
</div>
<div class="clearfix" style="margin-bottom:<?php echo $margin.'px'; ?> ">&nbsp;</div>
<?php $this->load->view("partial/footer"); ?>