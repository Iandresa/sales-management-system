<?php $this->load->view("partial/header_login_other"); ?>
   
<?php   if(!isset($lang))$lang='english'; ?>
<div id="content_area_wrapper">
<div id="content_area">
<?php echo form_open(site_url('register/newadviser/'.$lang)) ?>    
<div id="config_wrapper">
<fieldset id="register_info" style="width: 750px">
<legend><?php echo $this->lang->line("register_adviser_new"); ?></legend>

<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box">
    <?php echo validation_errors(); ?>
    </ul>

<div>
    <div style="display: inline;float: left;">
        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('login_username').':', 'user',array('class'=>'required','style'=>'width:115px')); ?>
                <div class="form_field">
                        <?php echo form_input(array(
                        'name'=>'username', 
                        'value'=>set_value('username'), 
                        'size'=>'20')); ?>
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('register_password').':', 'pass',array('class'=>'required','style'=>'width:115px')); ?>
                <div class="form_field">
                        <?php echo form_password(array(
                        'name'=>'password', 
                        'value'=>set_value('password'),
                        'size'=>'20')); ?>	
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('register_repeat_password').':', 'pass',array('class'=>'required','style'=>'width:115px')); ?>
                <div class="form_field">
                        <?php echo form_password(array(
                        'name'=>'password2', 
                        'value'=>set_value('password2'),
                        'size'=>'20')); ?>	
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_first_name').':', 'first_name',array('class'=>'required','style'=>'width:115px')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'first_name',
                        'id'=>'first_name',
                        'value'=>isset($person_info->first_name)?$person_info->first_name:"" )
                );?>
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_last_name').':', 'last_name',array('class'=>'required','style'=>'width:115px')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'last_name',
                        'id'=>'last_name',
                        'value'=>isset($person_info->last_name)?$person_info->last_name:"" )
                );?>
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_email').':', 'email',array('class'=>'required','style'=>'width:115px')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'email',
                        'id'=>'email',
                        'value'=>isset($person_info->email)?$person_info->email:"" )
                );?>
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_repeat_email').':', 'repeat_email',array('class'=>'required','style'=>'width:115px')); ?>
            <div class='form_field'>
            <?php echo form_input(array(
                    'name'=>'repeat_email',
                    'id'=>'repeat_email',
                    'value'=>isset($person_info->email)?$person_info->email:"")
            );?>
            </div>
        </div>
        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('employees_secret_question').':', 'secret_question',array('class'=>'required','style'=>'width:115px')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'secret_question',
                        'id'=>'secret_question',
                        'value'=>isset($secret_question)?$secret_question:"",'size'=>'20'));?>
                </div>
        </div>
        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('employees_secret_answer').':', 'secret_answer',array('class'=>'required','style'=>'width:115px')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'secret_answer',
                        'id'=>'secret_answer',
                        'value'=>isset($secret_answer)?$secret_answer:"",'size'=>'20'));?>
                </div>
        </div>     
    </div>

    <div style="display:inline;float: right">
             <div class="field_row clearfix">	
            <?php
            echo form_label(
sprintf($this->lang->line('register_adviser_agrewithterms'), 
                    anchor("login/show_menu/$lang/Terms",$this->lang->line('register_adviser_agrewithterms_this')))
					
					, 'terms',array('class'=>'wide2 required','style'=>'width:360px')); ?>
        </div>
        
        <div class="field_row clearfix"  style="margin-left: 70px">	
            <?php 
                echo form_input(array(
                    'name'=> 'accept',
                    'id'  => 'accept',
                    'value'=> 'yes',
                    'type' => 'radio'     
                ));
                echo $this->lang->line('register_adviser_yes');
                echo '&nbsp;&nbsp;';
                echo form_input(array(
                    'name'=> 'accept',
                    'id'  => 'accept',
                    'value'=> 'no',
                    'type' => 'radio',
                    'checked'=>'checked'
                ));
                echo $this->lang->line('register_adviser_no');
            ?>
          
        </div>
        
        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_phone_number').':', 'phone_number',array('class'=>'required')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'phone_number',
                        'id'=>'phone_number',
                        'value'=>isset($person_info->phone_number)?$person_info->phone_number:""));?>
                </div>
        </div>

        <div class="field_row clearfix">
            <?php echo form_label($this->lang->line('config_language').':', 'language',array('class'=>'required')); ?>
            <div class='form_field'>
            <?php         
                    $language = array('english' => 'English',
                                      'spanish' => 'Spanish');  
                    echo form_dropdown('language', $language);
                ?>
            </div>
        </div>
        
        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('config_timezone').':', 'timezone',array('class'=>'required')); ?>
                <div class='form_field'>
                <?php echo form_dropdown('timezone', 
                 array(
                        'Pacific/Midway'=>'(GMT-11:00) Midway Island, Samoa',
                        'America/Adak'=>'(GMT-10:00) Hawaii-Aleutian',
                        'Etc/GMT+10'=>'(GMT-10:00) Hawaii',
                        'Pacific/Marquesas'=>'(GMT-09:30) Marquesas Islands',
                        'Pacific/Gambier'=>'(GMT-09:00) Gambier Islands',
                        'America/Anchorage'=>'(GMT-09:00) Alaska',
                        'America/Ensenada'=>'(GMT-08:00) Tijuana, Baja California',
                        'Etc/GMT+8'=>'(GMT-08:00) Pitcairn Islands',
                        'America/Los_Angeles'=>'(GMT-08:00) Pacific Time (US & Canada)',
                        'America/Denver'=>'(GMT-07:00) Mountain Time (US & Canada)',
                        'America/Chihuahua'=>'(GMT-07:00) Chihuahua, La Paz, Mazatlan',
                        'America/Dawson_Creek'=>'(GMT-07:00) Arizona',
                        'America/Belize'=>'(GMT-06:00) Saskatchewan, Central America',
                        'America/Cancun'=>'(GMT-06:00) Guadalajara, Mexico City, Monterrey',
                        'Chile/EasterIsland'=>'(GMT-06:00) Easter Island',
                        'America/Chicago'=>'(GMT-06:00) Central Time (US & Canada)',
                        'America/New_York'=>'(GMT-05:00) Eastern Time (US & Canada)',
                        'America/Havana'=>'(GMT-05:00) Cuba',
                        'America/Bogota'=>'(GMT-05:00) Bogota, Lima, Quito, Rio Branco',
                        'America/Caracas'=>'(GMT-04:30) Caracas',
                        'America/Santiago'=>'(GMT-04:00) Santiago',
                        'America/La_Paz'=>'(GMT-04:00) La Paz',
                        'Atlantic/Stanley'=>'(GMT-04:00) Faukland Islands',
                        'America/Campo_Grande'=>'(GMT-04:00) Brazil',
                        'America/Goose_Bay'=>'(GMT-04:00) Atlantic Time (Goose Bay)',
                        'America/Glace_Bay'=>'(GMT-04:00) Atlantic Time (Canada)',
                        'America/St_Johns'=>'(GMT-03:30) Newfoundland',
                        'America/Araguaina'=>'(GMT-03:00) UTC-3',
                        'America/Montevideo'=>'(GMT-03:00) Montevideo',
                        'America/Miquelon'=>'(GMT-03:00) Miquelon, St. Pierre',
                        'America/Godthab'=>'(GMT-03:00) Greenland',
                        'America/Argentina/Buenos_Aires'=>'(GMT-03:00) Buenos Aires',
                        'America/Sao_Paulo'=>'(GMT-03:00) Brasilia',
                        'America/Noronha'=>'(GMT-02:00) Mid-Atlantic',
                        'Atlantic/Cape_Verde'=>'(GMT-01:00) Cape Verde Is.',
                        'Atlantic/Azores'=>'(GMT-01:00) Azores',
                        'Europe/Belfast'=>'(GMT) Greenwich Mean Time : Belfast',
                        'Europe/Dublin'=>'(GMT) Greenwich Mean Time : Dublin',
                        'Europe/Lisbon'=>'(GMT) Greenwich Mean Time : Lisbon',
                        'Europe/London'=>'(GMT) Greenwich Mean Time : London',
                        'Africa/Abidjan'=>'(GMT) Monrovia, Reykjavik',
                        'Europe/Amsterdam'=>'(GMT+01:00) Amsterdam, Berlin, Bern',
                        'Europe/Rome'=>'(GMT+01:00) Rome, Stockholm, Vienna,Prague',
                        'Europe/Belgrade'=>'(GMT+01:00)  Bratislava, Budapest, Ljubljana',
                        'Europe/Brussels'=>'(GMT+01:00)  Copenhagen, Madrid, Paris',
                        'Africa/Algiers'=>'(GMT+01:00) West Central Africa',
                        'Africa/Windhoek'=>'(GMT+01:00) Windhoek',
                        'Asia/Beirut'=>'(GMT+02:00) Beirut',
                        'Africa/Cairo'=>'(GMT+02:00) Cairo',
                        'Asia/Gaza'=>'(GMT+02:00) Gaza',
                        'Africa/Blantyre'=>'(GMT+02:00) Harare, Pretoria',
                        'Asia/Jerusalem'=>'(GMT+02:00) Jerusalem',
                        'Europe/Minsk'=>'(GMT+02:00) Minsk',
                        'Asia/Damascus'=>'(GMT+02:00) Syria',
                        'Europe/Moscow'=>'(GMT+03:00) Moscow, St. Petersburg, Volgograd',
                        'Africa/Addis_Ababa'=>'(GMT+03:00) Nairobi',
                        'Asia/Tehran'=>'(GMT+03:30) Tehran',
                        'Asia/Dubai'=>'(GMT+04:00) Abu Dhabi, Muscat',
                        'Asia/Yerevan'=>'(GMT+04:00) Yerevan',
                        'Asia/Kabul'=>'(GMT+04:30) Kabul',
                        'Asia/Yekaterinburg'=>'(GMT+05:00) Ekaterinburg',
                        'Asia/Tashkent'=>'(GMT+05:00) Tashkent',
                        'Asia/Kolkata'=>'(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi',
                        'Asia/Katmandu'=>'(GMT+05:45) Kathmandu',
                        'Asia/Dhaka'=>'(GMT+06:00) Astana, Dhaka',
                        'Asia/Novosibirsk'=>'(GMT+06:00) Novosibirsk',
                        'Asia/Rangoon'=>'(GMT+06:30) Yangon (Rangoon)',
                        'Asia/Bangkok'=>'(GMT+07:00) Bangkok, Hanoi, Jakarta',
                        'Asia/Krasnoyarsk'=>'(GMT+07:00) Krasnoyarsk',
                        'Asia/Hong_Kong'=>'(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
                        'Asia/Irkutsk'=>'(GMT+08:00) Irkutsk, Ulaan Bataar',
                        'Australia/Perth'=>'(GMT+08:00) Perth',
                        'Australia/Eucla'=>'(GMT+08:45) Eucla',
                        'Asia/Tokyo'=>'(GMT+09:00) Osaka, Sapporo, Tokyo',
                        'Asia/Seoul'=>'(GMT+09:00) Seoul',
                        'Asia/Yakutsk'=>'(GMT+09:00) Yakutsk',
                        'Australia/Adelaide'=>'(GMT+09:30) Adelaide',
                        'Australia/Darwin'=>'(GMT+09:30) Darwin',
                        'Australia/Brisbane'=>'(GMT+10:00) Brisbane',
                        'Australia/Hobart'=>'(GMT+10:00) Hobart',
                        'Asia/Vladivostok'=>'(GMT+10:00) Vladivostok',
                        'Australia/Lord_Howe'=>'(GMT+10:30) Lord Howe Island',
                        'Etc/GMT-11'=>'(GMT+11:00) Solomon Is., New Caledonia',
                        'Asia/Magadan'=>'(GMT+11:00) Magadan',
                        'Pacific/Norfolk'=>'(GMT+11:30) Norfolk Island',
                        'Asia/Anadyr'=>'(GMT+12:00) Anadyr, Kamchatka',
                        'Pacific/Auckland'=>'(GMT+12:00) Auckland, Wellington',
                        'Etc/GMT-12'=>'(GMT+12:00) Fiji, Kamchatka, Marshall Is.',
                        'Pacific/Chatham'=>'(GMT+12:45) Chatham Islands',
                        'Pacific/Tongatapu'=>'(GMT+13:00) Nuku\'alofa',
                        'Pacific/Kiritimati'=>'(GMT+14:00) Kiritimati'
                        ), date_default_timezone_get());
                        ?>
                </div>
        </div>
        
        
        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_address_1').':', 'address_1',array('class'=>'required')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'address_1',
                        'id'=>'address_1',
                        'value'=>isset($person_info->address_1)?$person_info->address_1:""));?>
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_address_2').':', 'address_2'); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'address_2',
                        'id'=>'address_2',
                        'value'=>isset($person_info->address_2)?$person_info->address_2:""));?>
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_city').':', 'city',array('class'=>'required')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'city',
                        'id'=>'city',
                        'value'=>isset($person_info->city)?$person_info->city:""));?>
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_state').':', 'state',array('class'=>'required')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'state',
                        'id'=>'state',
                        'value'=>isset($person_info->state)?$person_info->state:""));?>
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_zip').':', 'zip', array('class'=>'required')); ?>
                <div class='form_field'>
                <?php echo form_input(array(
                        'name'=>'zip',
                        'id'=>'zip',
                        'value'=>isset($person_info->zip)?$person_info->zip:""));?>
                </div>
        </div>

        <div class="field_row clearfix">	
        <?php echo form_label($this->lang->line('common_country').':', 'country',array('class'=>'wide required')); ?>
                <div class='form_field'>
                        <?php echo form_dropdown('country', $this->Country->get_all_array(),
                                isset($person_info->country)?$person_info->country:"Afghanistan", "style='width:160px;margin-left:-50px'");
                        ?>
                </div>
        </div>

        <div class="field_row clearfix">	
            <?php echo form_label($this->lang->line('common_comments').':', 'comments'); ?>
            <div class='form_field'>
                <?php echo form_textarea(array(
                        'name'=>'comments',
                        'id'=>'comments',
                        'value'=>isset($person_info->comments)?$person_info->comments:"",
                        'rows'=>'5',
                        'cols'=>'17')		
                );?>
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
    </div>
</div>   

</fieldset>
<div id="required_fields_message">

</div>
</div>
<?php
echo form_close();
?>
</div>
</div>
<?php $this->load->view("partial/footer"); ?>
</body>
</html>