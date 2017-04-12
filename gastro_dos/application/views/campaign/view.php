<script src="<?php echo base_url();?>js/ajaxupload.3.5.js" type="text/javascript" language="javascript" charset="UTF-8"></script>

<script type="text/javascript" >
	$(function(){
		var btnUpload1  = $('#upload_bottom1');
		var status1     = $('#status1');
                var uploading1 = $('#uploading1');
                var photo1     = $('#photo1');
                var image_error = $('#error_img');
		new AjaxUpload(btnUpload1, {
			action: '<?php echo site_url();?>/campaign/upload_photo/large',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                                        // extension is not allowed 
					status1.text('Only JPG, PNG or GIF files are allowed');
					return false;
				}
				status1.text('');
				uploading1.css('display', 'block');
				photo1.css('display', 'none');
                                $('#uploadfilehidden1').attr('value', null);
			},
			onComplete: function(file, response){
				//On completion clear the status
                                var res = eval("(" + response + ")");
				
                
				uploading1.css('display', 'none');
				//Add uploaded file to list
				if(res.success)
                                {
                                    var side_width    = <?php echo $this->config->item('banner_side_width') ?>;
                                    var side_height   = <?php echo $this->config->item('banner_side_height') ?>;
                                    var bottom_width  = <?php echo $this->config->item('banner_bottom_width') ?>;
                                    var bottom_height = <?php echo $this->config->item('banner_bottom_height') ?>;
                                    var login_width   = <?php echo $this->config->item('banner_login_big_width') ?>;
                                    var login_height  = <?php echo $this->config->item('banner_login_big_height') ?>;
                                    
                                    var sizeError = false;
                                    
                                   // alert('w:' + res.width + ' x h:' + res.height + ' t:'+res.type);
                                    var lista = document.getElementById("offer");
                                    var valorSeleccionado = lista.options[lista.selectedIndex].text;
                                    
                                    if(valorSeleccionado.indexOf('side') != -1 || valorSeleccionado.indexOf('lateral') != -1)
                                    {
                                        if(res.width != side_width || res.height != side_height)
                                            sizeError = true;
                                    }
                                    else if(valorSeleccionado.indexOf('bottom') != -1 || valorSeleccionado.indexOf('fondo') != -1)
                                    {
                                        if(res.width != bottom_width || res.height != bottom_height)
                                            sizeError = true;
                                    }
                                    else if(valorSeleccionado.indexOf('login') != -1 || valorSeleccionado.indexOf('comienzo') != -1)
                                    {
                                        if(res.width != login_width || res.height != login_height)
                                            sizeError = true;
                                    }
                                   
                                   if(!sizeError)
                                   {
                                        photo1.css('display', 'block');
                                        image_error.css('display', 'none');
                                        photo1.attr('src', '<?php echo base_url();?>/images/banners_pics/' + res.newfilename);
                                        $('#uploadfilehidden1').attr('value', 'ok');
                                   }
                                   else
                                   {
                                          photo1.css('display', 'none');
                                          image_error.css('display', 'block');
                                          status1.text("The image have not the required size.");
                                          $('#uploadfilehidden1').attr('value', null);
                                   }
                                } 
                                else
                                {
                                    status1.text(res.message);
                                    $('#uploadfilehidden1').attr('value', null);
				}
			}
		});     
	});
       
</script>

<script type="text/javascript" >
	$(function(){
		var btnUpload2  = $('#upload_bottom2');
		var status2    = $('#status2');
                var uploading2 = $('#uploading2');
                var photo2     = $('#photo2');
		new AjaxUpload(btnUpload2, {
			action: '<?php echo site_url();?>/campaign/upload_photo/small',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                                        // extension is not allowed 
					status2.text('Only JPG, PNG or GIF files are allowed');
					return false;
				}
				status2.text('');
				uploading2.css('display', 'block');
				photo2.css('display', 'none');
                                $('#uploadfilehidden2').attr('value', null);
			},
			onComplete: function(file, response){
				//On completion clear the status
                                var res = eval("(" + response + ")");
                               

                
				uploading2.css('display', 'none');
				//Add uploaded file to list
				if(res.success)
                                {
                                   // alert('w:' + res.width + ' x h:' + res.height + ' t:'+res.type);
                                    photo2.css('display', 'block');
                                    photo2.attr('src', '<?php echo base_url();?>/images/banners_pics/' + res.newfilename);
                                    $('#uploadfilehidden2').attr('value', 'ok');
                                } 
                                else
                                {
                                    status2.text(res.message);
                                    $('#uploadfilehidden2').attr('value', null);

				}
			}
		});     
	});
       
       
       
       
       
       
       
       
       
       
</script>

<script type="text/javascript">
 
 $(document).ready(function()  
 {  
    show(); 
 });
 
 function show()
 {
     var lista = document.getElementById("offer");
     // Obtener el texto que muestra la opci�n seleccionada
     var valorSeleccionado = lista.options[lista.selectedIndex].text;
     if(valorSeleccionado.indexOf('side') != -1)
     {
        $('#side_img').css('display','block');
        $('#bottom_img').css('display','none');
        $('#login_img_large').css('display','none');
     }
     else if(valorSeleccionado.indexOf('bottom') != -1)
     {        
         $('#side_img').css('display','none');
         $('#bottom_img').css('display','block');
         $('#login_img_large').css('display','none');
     }

     else if(valorSeleccionado.indexOf('login') != -1)
     {
         $('#side_img').css('display','none');
         $('#bottom_img').css('display','none');
         $('#login_img_large').css('display','block');
     }
 }
</script>

<?php
echo form_open('campaign/save/'.$campaign_info['campaign_id'],array('id'=>'campaign_form'));
?>
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="campaign_login_info" style="width: 330px">
<legend><?php echo $this->lang->line("campaign_info"); ?></legend>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_header_name').':', 'name',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'name',
		'id'=>'name',
		'value'=>$campaign_info['name']));?>
	</div>
</div>


<?php if(!$editing){ ?>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_header_offertype').':', 'offer'); ?>
	<div class='form_field'>
	<?php 
        echo form_label($offer_summary, 'offer', array('style'=>'width:200px;'));
    ?>
	</div>
</div>
<?php } ?>



<?php if($editing){ ?>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_header_offer').':', 'offer',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php 
            $js = 'id="offer" onChange="show();"';
            echo form_dropdown('offer', $offers_options, '',$js);
        ?>
	</div>
</div>
<?php } ?>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_header_link').':', 'link',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'link',
		'id'=>'link',
		'value'=>$campaign_info['link']));?>
	</div>
</div>


<?php if($editing){ ?>
<div class="field_row clearfix">	
<?php 
      echo "<div id='upload_bottom1' style='float:left'>";
          echo form_label($this->lang->line('campaign_header_image').'', 'image_large',array('class'=>'required'));
          echo "<img alt='upload image' width='13px' height='13px' style='margin-left:-55px;margin-top:8px ' border='0' src='".base_url().'images/insertar.png'."' />";  
      echo "</div>";
      
  ?>
    <input type="hidden" id="uploadfilehidden1" name="uploadfilehidden1"/>
	<div class='form_field'>
        
        
        <span id="side_img" style="display: none"><?php printf($this->lang->line('campaign_header_imagesize'), $this->config->item('banner_side_width'), $this->config->item('banner_side_height')); ?></span>
        <span id="bottom_img" style="display: none"><?php printf($this->lang->line('campaign_header_imagesize'), $this->config->item('banner_bottom_width'), $this->config->item('banner_bottom_height')); ?></span>
        <span id="login_img_large" style="display: none"><?php printf($this->lang->line('campaign_header_imagesize'), $this->config->item('banner_login_big_width'), $this->config->item('banner_login_big_height')); ?></span>
      
        <span id="error_img" style="display: none; color: red"><?php echo $this->lang->line('campaign_header_image_error'); ?></span>
        <img id="uploading1" alt="..." style="display: none" src="<?php echo base_url();?>/images/uploading.gif"/>
        <img id="photo1" alt="photo" height="30" width="50" border="0" style="display: none" />
	</div>
</div>
<?php } ?>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_header_tooltip').':', 'tooltip'); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tooltip',
		'id'=>'tooltip',
		'value'=>$campaign_info['tooltip']));?>
	</div>
</div>



<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_header_dailytop').':', 'daily_top'); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'daily_top',
		'id'=>'daily_top',
		'value'=>$campaign_info['daily_top']));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_header_receivestadistics').':', 'receive_stadistics'); ?>
	<div class='form_field'>
	<?php echo form_checkbox('receive_stadistics','1', $campaign_info['receive_stadistics'], "id='receive_stadistics' disabled='true'"); ?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('campaign_header_isactive').':', 'is_active'); ?>
	<div class='form_field'>
	<?php echo form_checkbox('is_active','1',$campaign_info['is_active'], "id='is_active'"); ?>
	</div>
</div>



<div>
	<dl>
	
		<dt>
		<?php echo form_checkbox("lunes", 0, ($this->uri->segment(3)==-1) ? TRUE : $this->auxiliar->is_checked($campaign_info['week_days'],0),"disabled='true'").' '.form_label($this->lang->line('campaign_Monday'),"lunes"); ?>
		</dt>
		
		<dt>
		<?php echo form_checkbox("martes", 1, ($this->uri->segment(3)==-1) ? TRUE : $this->auxiliar->is_checked($campaign_info['week_days'],1),"disabled='true'").' '.form_label($this->lang->line('campaign_Tuesday'),"martes"); ?>
		</dt>
		
		<dt>
		<?php echo form_checkbox("miercoles", 2, ($this->uri->segment(3)==-1) ? TRUE : $this->auxiliar->is_checked($campaign_info['week_days'],2),"disabled='true'").' '.form_label($this->lang->line('campaign_Wednesday'),"miercoles"); ?>
		</dt>
		
		<dt>
		<?php echo form_checkbox("jueves", 3, ($this->uri->segment(3)==-1) ? TRUE : $this->auxiliar->is_checked($campaign_info['week_days'],3),"disabled='true'").' '.form_label($this->lang->line('campaign_Thursday'),"jueves"); ?>
		</dt>
		
		<dt>
		<?php echo form_checkbox("viernes", 4, ($this->uri->segment(3)==-1) ? TRUE : $this->auxiliar->is_checked($campaign_info['week_days'],4),"disabled='true'").' '.form_label($this->lang->line('campaign_Friday'),"viernes"); ?>
		</dt>
		
		<dt>
		<?php echo form_checkbox("sabado", 5, ($this->uri->segment(3)==-1) ? TRUE : $this->auxiliar->is_checked($campaign_info['week_days'],5),"disabled='true'").' '.form_label($this->lang->line('campaign_Saturday'),"sabado"); ?>
		</dt>
		
		<dt>
		<?php echo form_checkbox("domingo", 6, ($this->uri->segment(3)==-1) ? TRUE : $this->auxiliar->is_checked($campaign_info['week_days'],6),"disabled='true'").' '.form_label($this->lang->line('campaign_Sunday'),"domingo"); ?>
		</dt>
		
	</dl>
</div>




<?php
echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>$this->lang->line('common_submit'),
	'class'=>'submit_button float_right')
);

?>

</fieldset>
<?php 
echo form_close();
?>
<script type='text/javascript'>
//validation and submit handling
$(document).ready(function()
{
	$('#campaign_form').validate({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_campaign_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			name: "required",
                        link: {required:true, url:true},
			uploadfilehidden1: "required",
			uploadfilehidden2: "required"
   		},
		messages: 
		{
                    name: "<?php echo $this->lang->line('common_name_required'); ?>",
                    link: "<?php echo $this->lang->line('common_link_required'); ?>",
                    uploadfilehidden1: "<?php echo $this->lang->line('common_image_required'); ?>",
                    uploadfilehidden2: "<?php echo $this->lang->line('common_image_required'); ?>"
		}
	});
});
</script>