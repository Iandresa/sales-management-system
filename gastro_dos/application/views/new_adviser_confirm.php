<div style="margin-top: 10px;">
    <?php
         echo $this->lang->line('advisers_new_confirm');
    ?>
</div>

<table style="margin-top: 40px; width: 100%">
    <tr>
        <td>
            <a href="<?php echo site_url('register/newadviser/'.$lang); ?>" style="text-decoration: none" target="_blank">
                <div class='big_button2'>
                    <span style="font-size: 10px"><?php echo $this->lang->line('advisers_crete_new');?></span>
                </div>
            </a>
        </td>
        <td>
             <a href="<?php echo site_url('register/login_adviser'); ?>" id="TB_closeWindowButton"  style="text-decoration: none">
                <div class='big_button2'>
                    <span style="font-size: 10px"><?php echo $this->lang->line('login_login');?></span>
                </div>
            </a>
        </td>
    </tr>
</table>






