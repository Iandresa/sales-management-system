<?php
$this->load->view("partial/header");
?>
<div id="page_title" style="margin-bottom:8px;"><?php echo $title ?></div>
<div id="page_subtitle" style="margin-bottom:8px;"><?php echo $subtitle ?></div>
<div style="text-align: center;">

<script type="text/javascript">
swfobject.embedSWF(
"<?php echo base_url(); ?>open-flash-chart.swf", "chart",
"775", "420", "9.0.0", "expressInstall.swf",
{"data-file":"<?php echo $data_file; ?>"},{wmode: "opaque"
  } )
</script>
<?php
?>
</div>
<div id="chart_wrapper" >
    <div id="chart"></div>
</div>
<div id="table_holder">
    <table class="tablesorter report" id="sortable_table">
        <tbody>
            <?php
                if(isset($summary_data))
                    echo $summary_data;
            ?>    
        </tbody>
    </table>
</div>
<!--<div id="report_summary">
<?php foreach($summary_data as $name=>$value) { ?>
	<div class="summary_row"><?php echo $this->lang->line('reports_'.$name). ': '.to_currency($value); ?></div>
<?php }?>
</div>-->
</br>     
<div style="text-align: center;">
    <img width="75px" src="images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png" 
         onmouseover="this.src='images/logotipo/<?php echo $this->lang->line('login_over'); ?>.png'" 
         onmouseout="this.src='images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png'" 
         border="0">
</div>
<?php
$this->load->view("partial/footer"); 
?>