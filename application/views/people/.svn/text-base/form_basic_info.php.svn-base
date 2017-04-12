<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_first_name').':', 'first_name',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'first_name',
		'id'=>'first_name',
		'value'=>$person_info->first_name)
	);?>
	</div>
</div>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_last_name').':', 'last_name',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'last_name',
		'id'=>'last_name',
		'value'=>$person_info->last_name)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_email').':', 'email',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'email',
		'id'=>'email',
		'value'=>$person_info->email)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_repeat_email').':', 'repeat_email',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'repeat_email',
		'id'=>'repeat_email',
		'value'=>$person_info->email)
	);?>
	</div>
</div>

<?php

$CI = & get_instance();
if($CI->Employee->is_AdviserUser($person_info->person_id))
{
?>
    <div class="field_row clearfix">
        <?php echo form_label($this->lang->line('config_language').':', 'language',array('class'=>'required')); ?>
        <div class='form_field'>
        <?php         
                $languages = array('english' => 'English',
                                  'spanish' => 'Spanish');  
                echo form_dropdown('language', $languages, $lang);
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
                ), $timezone);
                ?>
        </div>
    </div>
<?php
}
?>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_phone_number').':', 'phone_number',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'phone_number',
		'id'=>'phone_number',
		'value'=>$person_info->phone_number));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_address_1').':', 'address_1',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'address_1',
		'id'=>'address_1',
		'value'=>$person_info->address_1));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_address_2').':', 'address_2'); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'address_2',
		'id'=>'address_2',
		'value'=>$person_info->address_2));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_city').':', 'city',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'city',
		'id'=>'city',
		'value'=>$person_info->city));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_state').':', 'state',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'state',
		'id'=>'state',
		'value'=>$person_info->state));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_zip').':', 'zip',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'zip',
		'id'=>'zip',
		'value'=>$person_info->zip));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_country').':', 'country',array('class'=>'wide required')); ?>
	<div class='form_field'>
		<?php echo form_dropdown('country', $this->Country->get_all_array(),
			$person_info->country, "style='width:160px;margin-left:-50px'");
		?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_comments').':', 'comments'); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'comments',
		'id'=>'comments',
		'value'=>$person_info->comments,
		'rows'=>'5',
		'cols'=>'17')		
	);?>
	</div>
</div>