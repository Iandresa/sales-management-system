<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function() 
{ 
    init_table_sorting();
    enable_select_all();
    enable_checkboxes();
    //enable_row_selection();
    enable_delete('<?php echo $this->lang->line($controller_name."_confirm_delete")?>','<?php echo $this->lang->line($controller_name."_none_selected")?>');
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
				0: { sorter: false}, 
				7: { sorter: false} 
			} 

		}); 
	}
}

function post_person_form_submit(response)
{
	if(!response.success)
	{
		set_feedback(response.message,'error_message',true);	
	}
	else
	{
		//This is an update, just update one row
		if(jQuery.inArray(response.person_id,get_visible_checkbox_ids()) != -1)
		{
			update_row(response.person_id,'<?php echo site_url("$controller_name/get_row")?>');
			set_feedback(response.message,'success_message',false);	
			
		}
		else //refresh entire table
		{
			do_search(true,function()
			{
				//highlight new row
				hightlight_row(response.person_id);
				set_feedback(response.message,'success_message',false);		
			});
		}
	}
}

function show_hide_search_filter(search_filter_section, switchImgTag) {
    var ele = document.getElementById(search_filter_section);
    var imageEle = document.getElementById(switchImgTag);
    var elesearchstate = document.getElementById('search_section_state');
    if(ele.style.display == "block")
    {
        ele.style.display = "none";
        imageEle.innerHTML = '<img src=" <?php echo base_url()?>images/plus.png" style="border:0;outline:none;padding:0px;margin:0px;position:relative;top:-5px;" >';
        elesearchstate.value="none";
    }
    else
    {
        ele.style.display = "block";
        imageEle.innerHTML = '<img src=" <?php echo base_url()?>images/minus.png" style="border:0;outline:none;padding:0px;margin:0px;position:relative;top:-5px;" >';
        elesearchstate.value="block";
    }
}
</script>

<div id="title_bar">
    <div id="title" class="float_left"><?php echo $this->lang->line('sales_histirical'); ?></div>
</div>

<?php 
if(!$this->Employee->is_AdviserUser($this->session->userdata('person_id')))
{?>
    <div id="titleTextImg" style="background-color:#EEEEEE;height:20px;position:relative;">
        <div style="float:left;vertical-align:text-top;"><?php echo $this->lang->line('items_options')//Search Options ?> :</div>
        <a id="imageDivLink" href="javascript:show_hide_search_filter('search_filter_section', 'imageDivLink');" style="outline:none;">
            <img src="
	<?php echo isset($search_section_state)?  ( ($search_section_state)? base_url().'images/minus.png' : base_url().'images/plus.png') : base_url().'images/plus.png';?>" style="border:0;outline:none;padding:0px;margin:0px;position:relative;top:-5px;"></a>
    </div>

    <div id="search_filter_section" style="display: <?php echo isset($search_section_state)?  ( ($search_section_state)? 'block' : 'none') : 'none';?>;background-color:#EEEEEE;">
        <?php echo form_open("sales/select_sale",array('id'=>'select_sale_form')); ?>
        <?php echo form_label($this->lang->line('sales_bill_id').' '.':', 'id_bill');?>
        <?php echo form_input(array('name'=>'sale','id'=>'sale','size'=>'15','value'=>$this->lang->line('sales_start_typing_bill_id')));?>
        <!--<input type="hidden" name="search_section_state" id="search_section_state" value="<?php echo isset($search_section_state)?  ( ($search_section_state)? 'block' : 'none') : 'none';?>" />-->
        <span style="float:right;"><?php echo anchor("sales/show_all","<div class='small_button'><span>".$this->lang->line('recvs_show_all')."</span></div>",
                array('title'=>$this->lang->line('recvs_show_all')));
            ?></span>
        <div style="height: 10px;"></div>
        </form>
    </div>

    <div id="table_action_header">
        <?php  echo form_open("sales/change_mode",array('id'=>'mode_form')); ?>
	<span><?php echo $this->lang->line('sales_mode') ?></span>
        <?php echo form_dropdown('mode',$modes , $mode,'onchange="$(\'#mode_form\').submit();"'); ?>
    </form>
    </div>
<?php
}?>

<div id="table_holder">
<?php echo $manage_table; ?>
</div>
<div id="feedback_bar"></div>
<div class="clearfix" style="margin-bottom:140px;">&nbsp;</div>
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
    $(document).ready(function()
    {
        $('#sale').click(function()
        {
            $(this).attr('value','');
        });

        $("#sale").autocomplete('<?php echo site_url("sales/sale_search"); ?>',
            {
                minChars:0,
                delay:10,
                max:100,
                formatItem: function(row) {
                    return row[1];
                }
            });

        $("#sale").result(function()
        {
            $("#select_sale_form").submit();
        });

        $('#sale').blur(function()
        {
            $(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_bill_id'); ?>");
        });
    });
</script>