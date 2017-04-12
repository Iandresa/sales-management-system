<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{   
    init_table_sorting();
    enable_select_all();
    enable_row_selection();
    //enable_search('<?php echo site_url("$controller_name/suggest")?>','<?php echo $this->lang->line("common_confirm_search")?>');
    //enable_email('<?php echo site_url("$controller_name/mailto")?>');
    //enable_delete('<?php echo $this->lang->line($controller_name."_confirm_delete")?>','<?php echo $this->lang->line($controller_name."_none_selected")?>');   
});

function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(
		{
			sortList: [[1,0]],
			headers:
			{
				4: { sorter: false},
                                5: { sorter: false}
			}

		});
	}
}

function post_subsidary_form_submit(response)
{
    
	if(!response.success)
	{
		set_feedback(response.message,'error_message',true);
	}
	else
        {
                //This is an updaalert(response.subsidary_id);te, just update one row
                if(jQuery.inArray(response.subsidary_id,get_visible_checkbox_ids()) != -1)
		{
            
			update_row(response.subsidary_id,'<?php echo site_url("$controller_name/get_row")?>');
			set_feedback(response.message,'success_message',false);

		}
		else //refresh entire table
		{
			do_search(true,function()
			{
				//highlight new row
				hightlight_row(response.subsidary_id);
				set_feedback(response.message,'success_message',false);
			});
		}
	}
}
</script>

<div id="title_bar">
	<div id="title" class="float_left"><?php echo $this->lang->line('common_list_of').' '.$this->lang->line('module_'.$controller_name); ?></div>
	<div id="new_button">
		<?php echo anchor("$controller_name/view/-1/width:$form_width",
		"<div class='big_button'><span>".$this->lang->line($controller_name.'_new')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line($controller_name.'_new')));
		?>
	</div>
</div>


 

<div id="table_holder" style="margin-bottom:<?php echo $margin.'px'; ?> ">
	<?php echo $manage_table; ?>
</div>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>



<?php if(isset($success_msg) && $success_msg){ ?>
    <script type="text/javascript" language="javascript">
        set_feedback('<?php echo $success_msg; ?>', '<?php echo $success == 1 ? 'success_message' :'error_message'; ?>',false);
    </script>
<?php } ?>  