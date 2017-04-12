<?php
    echo form_open("sales/set_dispatch_date",array('id'=>'date_form'));
    echo form_input(array('name' => 'hidden_date','id' => 'hidden_date','value' => $pDate,'type' => 'hidden'));
    echo form_input(array('name' => 'hidden_datecmp','id' => 'hidden_datecmp','value' => $datecmp,'type' => 'hidden'));
?>
<table style="margin-top: 25px; width: 100%;text-align: center">
    <tr>
        <td>
            <?php
                echo form_input(array('name'=>'date','id'=>'date','size'=>'15'));
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <div class='small_button' id='set_date_button' style='margin-top:10px; margin-left: 100px;'
                 title="<?php echo $this->lang->line('sales_set_date'); ?>">
                <span>
                    <?php
                    echo $this->lang->line('sales_set_date');
                    ?>
                </span>
            </div>
        </td>
    </tr>
</table>
</form>

<script type="text/javascript" language="javascript">
    $(document).ready(function()
    {
        var $datecmp = $('#hidden_datecmp').val();
        var $pDate = $('#hidden_date').val();
        <?php if($lang=="spanish") { ?>
            $('#date').appendDtpicker({"inline": true,"locale": "es","current": $pDate});
        <?php } else { ?>
            $('#date').appendDtpicker({"inline": true,"current": $pDate});
        <?php } ?>
            
        $('#date').attr('readonly','true');
        
        $("#set_date_button").click(function()
        {   
            if($('#date').val() < $datecmp)
                alert("<?php echo $this->lang->line("sales_date_error"); ?>");
            else
                $('#date_form').submit();
        });
    });
</script>







