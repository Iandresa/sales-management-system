
<div style="margin-top: 10px;">
    <?php
        if(!$module_report)
            echo $this->lang->line('sales_activate_module_msg');
        else
            echo $this->lang->line('sales_activate_module_msg2');
    ?>
</div>

<table style="margin-left: 65px; margin-top: 40px; width: 50%">
    <tr>
        <td>
            <a href="<?php echo site_url('dineroMailer'); ?>" style="text-decoration: none" target="_blank">
                <div class='big_button2'>
                    <span style="font-size: 11px"><?php echo $this->lang->line('common_acquire_modules');?></span>
                </div>
            </a>
        </td>
    </tr>
</table>







