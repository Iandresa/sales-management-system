<?php $this->load->view("partial/header"); ?>
<div id="page_title" style="margin-bottom:8px;"><?php echo $this->lang->line('reports_reports'); ?></div>
<div id="welcome_message"><?php echo $this->lang->line('reports_welcome_message'); ?>
<ul id="report_list">
<!-- abrir 1 -->
	<li><h3><?php echo $this->lang->line('reports_graphical_reports'); ?></h3>
	 
		<ul>
			<li><a href="<?php echo site_url('reports/graphical_summary_sales');?>"><?php echo $this->lang->line('reports_sales'); ?></a></li>
			<?php 
                            if($this->Enterprise->get_permi_gr_reports($this->session->userdata('enterprise_id'))!='0'){?>
                                <li><a href="<?php echo site_url('reports/graphical_summary_categories');?>"><?php echo $this->lang->line('reports_categories'); ?></a></li>
			<?php } else {?>
                                 <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                        "<span>".$this->lang->line('reports_categories')."</span>",
                                                        array('class'=>'thickbox none','title'=>$this->lang->line('reports_categories'))); ?></li>
                        <?php } ?>
                        <li><a href="<?php echo site_url('reports/graphical_summary_customers');?>"><?php echo $this->lang->line('reports_customers'); ?></a></li>
			<li><a href="<?php echo site_url('reports/graphical_summary_suppliers');?>"><?php echo $this->lang->line('reports_suppliers'); ?></a></li>
                        <?php if($this->Enterprise->get_permi_gr_reports($this->session->userdata('enterprise_id'))!='0'){?>
                            <li><a href="<?php echo site_url('reports/graphical_summary_items');?>"><?php echo $this->lang->line('reports_items2'); ?></a></li>
                            <li><a href="<?php echo site_url('reports/graphical_summary_employees');?>"><?php echo $this->lang->line('reports_employees'); ?></a></li>
                            <li><a href="<?php echo site_url('reports/graphical_summary_taxes');?>"><?php echo $this->lang->line('reports_taxes'); ?></a></li>
                            <!--<li><a href="<?php echo site_url('reports/graphical_summary_discounts');?>"><?php echo $this->lang->line('reports_discounts'); ?></a></li>-->
                            <li><a href="<?php echo site_url('reports/graphical_summary_payments');?>"><?php echo $this->lang->line('reports_payments'); ?></a></li>
                        <?php } else {?>
                            <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                   "<span>".$this->lang->line('reports_items2')."</span>",
                                                   array('class'=>'thickbox none','title'=>$this->lang->line('reports_items2'))); ?></li>
                            <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                   "<span>".$this->lang->line('reports_employees')."</span>",
                                                   array('class'=>'thickbox none','title'=>$this->lang->line('reports_employees'))); ?></li>
                            <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                   "<span>".$this->lang->line('reports_taxes')."</span>",
                                                   array('class'=>'thickbox none','title'=>$this->lang->line('reports_taxes'))); ?></li>
                            <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                   "<span>".$this->lang->line('reports_payments')."</span>",
                                                   array('class'=>'thickbox none','title'=>$this->lang->line('reports_payments'))); ?></li>
                        <?php } ?>
                </ul>	
	
	</li>
<!-- cerrar 1 -->
	<li><h3><?php echo $this->lang->line('reports_summary_reports'); ?></h3>
		<ul>
			<li><a href="<?php echo site_url('reports/summary_sales');?>"><?php echo $this->lang->line('reports_sales'); ?></a></li>
                        <?php if($this->Enterprise->get_permi_gr_reports($this->session->userdata('enterprise_id'))!='0'){?>    
                            <li><a href="<?php echo site_url('reports/summary_categories');?>"><?php echo $this->lang->line('reports_categories'); ?></a></li>
			<?php } else {?>
                            <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                   "<span>".$this->lang->line('reports_categories')."</span>",
                                                   array('class'=>'thickbox none','title'=>$this->lang->line('reports_categories'))); ?></li>
                        <?php } ?>
                        <li><a href="<?php echo site_url('reports/summary_customers');?>"><?php echo $this->lang->line('reports_customers'); ?></a></li>
			<li><a href="<?php echo site_url('reports/summary_suppliers');?>"><?php echo $this->lang->line('reports_suppliers'); ?></a></li>
			<?php if($this->Enterprise->get_permi_gr_reports($this->session->userdata('enterprise_id'))!='0'){?>
                            <li><a href="<?php echo site_url('reports/summary_items');?>"><?php echo $this->lang->line('reports_items2'); ?></a></li>
                            <li><a href="<?php echo site_url('reports/summary_employees');?>"><?php echo $this->lang->line('reports_employees'); ?></a></li>
                            <li><a href="<?php echo site_url('reports/summary_taxes');?>"><?php echo $this->lang->line('reports_taxes'); ?></a></li>
                            <!--<li><a href="<?php echo site_url('reports/summary_discounts');?>"><?php echo $this->lang->line('reports_discounts'); ?></a></li>-->
                            <li><a href="<?php echo site_url('reports/summary_payments');?>"><?php echo $this->lang->line('reports_payments'); ?></a></li>
                        <?php } else {?>
                            <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                   "<span style=''>".$this->lang->line('reports_items2')."</span>",
                                                   array('class'=>'thickbox none','title'=>$this->lang->line('reports_items2'))); ?></li>
                            <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                   "<span>".$this->lang->line('reports_employees')."</span>",
                                                   array('class'=>'thickbox none','title'=>$this->lang->line('reports_employees'))); ?></li>
                            <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                   "<span>".$this->lang->line('reports_taxes')."</span>",
                                                   array('class'=>'thickbox none','title'=>$this->lang->line('reports_taxes'))); ?></li>
                            <li id="underline"> <?php echo anchor("sales/activate_module_report/width:300/height:180",
                                                   "<span>".$this->lang->line('reports_payments')."</span>",
                                                   array('class'=>'thickbox none','title'=>$this->lang->line('reports_payments'))); ?></li>
                        <?php } ?>
                </ul>
	</li>
	
<!-- 
	 <li><h3><?php echo $this->lang->line('reports_detailed_reports'); ?></h3>
		<ul>
			<li><a href="<?php echo site_url('reports/detailed_sales');?>"><?php echo $this->lang->line('reports_sales'); ?></a></li>
			<li><a href="<?php echo site_url('reports/detailed_receivings');?>"><?php echo $this->lang->line('reports_receivings'); ?></a></li>
			<li><a href="<?php echo site_url('reports/specific_customer');?>"><?php echo $this->lang->line('reports_customer'); ?></a></li>
			<li><a href="<?php echo site_url('reports/specific_employee');?>"><?php echo $this->lang->line('reports_employee'); ?></a></li>
		</ul>
	
	</li>
	
	<li><h3><?php echo $this->lang->line('reports_inventory_reports'); ?></h3>
		<ul>
			<li><a href="<?php echo site_url('reports/inventory_low');?>"><?php echo $this->lang->line('reports_low_inventory'); ?></a></li>
			<li><a href="<?php echo site_url('reports/inventory_summary');?>"><?php echo $this->lang->line('reports_inventory_summary'); ?></a></li>
		</ul>
	</li>
	
	 -->
</ul>
<br>
</div>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>
<div class="clearfix" style="margin-bottom:<?php echo $margin.'px'; ?>">&nbsp;</div>
<?php $this->load->view("partial/footer") ; ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
});
</script>
