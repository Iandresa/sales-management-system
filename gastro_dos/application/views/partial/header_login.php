<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<base href="<?php echo base_url();?>" />
<title>IANDRESA Sale System <?php echo $this->lang->line('login_login'); ?></title>

<link rel="shortcut icon" href="images/icos/iandresa.ico" type="image/x-icon" />

<link rel="stylesheet" rev="stylesheet" href="css/login.css" />
<link rel="stylesheet" rev="stylesheet" href="css/index.css" />
<link rel="stylesheet" rev="stylesheet" href="css/phppos.css" />

<script src="js/jquery-1.2.6.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>

<script src="js/common.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/jquery.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/thickbox1.js" type="text/javascript" language="javascript" charset="UTF-8"></script>



<link rel="stylesheet" rev="stylesheet" href="css/theme-minimalist-square.css"  media="screen" />

<script src="js/slideViewer1.2.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/jquery.easing.1.3.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<link rel="stylesheet" rev="stylesheet" href="css/slideViewer.css"  media="screen" />
<script type="text/javascript">
$(document).ready(function()
{
	$("#login_form input:first").focus();
});
</script>

<script type="text/javascript">
    $(window).bind("load", function() {
    $("div#slideViewer").slideView();
});
    function slider() {
    $("div#slideViewer").trigger('click'); // where #mygalone is the id of your images main div container
    }
    var slideOn = setInterval("slider()",3000); // change every 3 seconds.
</script>

<?php
 $this->tasklib->DailyTask_Checker();
?>
