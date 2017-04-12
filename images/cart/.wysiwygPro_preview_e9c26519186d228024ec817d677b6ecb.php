<?php
if ($_GET['randomId'] != "MViW78iQP35Ouj1EHHpUdhSCuFEzupX6HzbkwaTwQQ2oJGQPlXavTLUMCj3wvXTm") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
