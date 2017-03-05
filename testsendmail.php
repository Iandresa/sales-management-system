<?php

$headers = "Content-Type: text/html; charset=utf-8\r\n";
$headers .= "From: support@iandresa.com";
$body = "<html>
<body>
Hello {client_name}:<br>
<br>
We hope that you are using the additional modules during this trial month. We would like to remind you that there is only {days_trial} days left before the trial period expired.<br>
<br>
Remember that with the Order and Delivery modules are easier to follow up an order in progress and link it with your sales.<br>
<br>
In addition, the additional reports of Categories, Items, Employees, Taxes, Discounts, and Payments, you are going to have more information for decision-making in your business.<br>
<br>
How you can purchase additional modules and/or advertisement at IANDRESA?<br>
If you would like to purchase additional modules and/or advertisement, click in the link below to see our prices:<br>
<br>
https://www.iandresa.com/<compra><br>
<br>
What can you do with IANDRESA? <br>
<ul>
<li>Manage your Clients, Items, Suppliers, Warehouse, Employees and Sales.</li>
<li>Generate reports of your Sales, Suppliers and Clients.</li>
<li>Create other branches in the case you may have or want to have more shops.</li>
<li>A free trial by one month the additional modules of Orders and/or Delivery, and purchase them in case they fit you business needs.</li>
<li>A free trial by one month the additional reports of Categories, Items, Employees, Taxes, Discounts, and Payments, and purchase them in case they fit you business needs.</li>
<li>Purchase banners in order to advertise and promote your product and/or services.</li>
<li>Request a custom development according to the needs of your business.</li>
<li>In brief, you can manage your business from Internet.</li>
</ul>
<br>
How you can start to manage and/or advertise your business? <br>
Log on to http://www.iandresa.com with your username and password, and start to manage your business from Internet.<br>
<br>
Did you know that advertise your product and/or service at IANDRESA is very important? <br>
There are several companies use daily IANDRESA as Sales Management System to manage their business. <br>
<br>
IANDRESA’s clients will see your product and/or service at IANDRESA. In case you are already advertising in other Medias, you can extend your audience through IANDRESA and reach your potential clients before they know of your product and/or service.<br>
<br>
How can I recover my username and/or password?<br>
If you forgot your username and/or password, you can recover it/them by clicking in the link below:<br>
<br>
http://www.iandresa.com<recuperar>.<br>
<br>
If your email program doesn’t allow you to clink on these links, please Copy the link text and paste it/them in the address bar of your internet browser program.<br>
<br>
Best Regards, <br>
IANDRESA (We are here) <br>
<br>
<img alt='IANDRESA' src='https://www.iandresa.com/images/logotipo/iandresalogomouse.png'/><br>
<br>
For inquiries and questions, please contact us via support@iandresa.com
</body>
</html>
";

echo mail("ariel@icid.cu", "Test subject", $body, $headers ); 


?>
