<?
session_start();

include("db/mysql.php");
include("classes/all.php");
include("funciones.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gastro</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<style type="text/css">
        body { font-family:Lucida Sans, Lucida Sans Unicode, Arial, Sans-Serif; font-size:13px; margin:0px auto;}
        #tabs { margin:0; padding:0; list-style:none; overflow:hidden; }
        #tabs li { float:left; display:block; padding:5px; background-color:#bbb; margin-right:5px;}
        #tabs li a { color:#fff; text-decoration:none; }
        #tabs li.current { background-color:#e1e1e1;}
        #tabs li.current a { color:#000; text-decoration:none; }
        #tabs li a.remove { color:#f00; margin-left:10px;}
        #content { background-color:#e1e1e1;}
        #content p { margin: 0; padding:20px 20px 100px 20px;}
        
        #main { width:900px; margin:0px auto; overflow:hidden;background-color:#F6F6F6; margin-top:20px;
             -moz-border-radius:10px;  -webkit-border-radius:10px; padding:30px;}
        #wrapper, #doclist { float:left; margin:0 20px 0 0;}
        #doclist { width:150px;}
        #doclist ul { margin:0; list-style:none;}
        #doclist li { margin:0; padding:0;}
        #documents { margin:0; padding:0;}
        
        #wrapper { width:700px; margin-top:20px;}
            
        #header{ background-color:#F6F6F6; width:900px; margin:0px auto; margin-top:20px;
             -moz-border-radius:10px;  -webkit-border-radius:10px; padding:30px; position:relative;}
        #header h2 {font-size:16px; font-weight:normal; margin:0px; padding:0px;}

    </style>
    
    <script type="text/javascript" src="js/lib/jquery.js" ></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#browser a").click(function() {
                addTab($(this));
            });

            $('#tabs a.tab').live('click', function() {
                // Get the tab name
                var contentname = $(this).attr("id") + "_content";

                // hide all other tabs
                $("#content p").hide();
                $("#tabs li").removeClass("current");

                // show current tab
                $("#" + contentname).show();
                $(this).parent().addClass("current");
            });

            $('#tabs a.remove').live('click', function() {
                // Get the tab name
                var tabid = $(this).parent().find(".tab").attr("id");

                // remove tab and related content
                var contentname = tabid + "_content";
                $("#" + contentname).remove();
                $(this).parent().remove();

                // if there is no current tab and if there are still tabs left, show the first one
                if ($("#tabs li.current").length == 0 && $("#tabs li").length > 0) {

                    // find the first tab    
                    var firsttab = $("#tabs li:first-child");
                    firsttab.addClass("current");

                    // get its link name and show related content
                    var firsttabid = $(firsttab).find("a.tab").attr("id");
                    $("#" + firsttabid + "_content").show();
                }
            });
        });
        function addTab(link) {
            // If tab already exist in the list, return
            if ($("#" + $(link).attr("rel")).length != 0)
                return;
            
            // hide other tabs
            $("#tabs li").removeClass("current");
            $("#content p").hide();
            
            // add new tab and related content
            $("#tabs").append("<li class='current'><a class='tab' id='" +
                $(link).attr("rel") + "' href='#'>" + $(link).html() + 
                "</a><a href='#' class='remove'>x</a></li>");

            $("#content").append("<p id='" + $(link).attr("rel") + "_content'>" + 
                "<iframe name='" + $(link).attr("rel") + "' src='" + $(link).attr("title") + "' frameborder=0 width='100%' height='400'></iframe></p>");

            
            // set the newly added tab as current
            $("#" + $(link).attr("rel") + "_content").show();
        }
    </script>
<script type="text/javascript" src="simpletreemenu.js">

/***********************************************
* Simple Tree Menu- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<link rel="stylesheet" type="text/css" href="simpletree.css" />
    
    
<?
$id = $_GET['id'];

$client = new cliente();
$rec = new recinto();
$prov = new proveedor();

// Consultamos en la BD los datos del cliente.
$user = $prov->getDataProv($id);
$nombre = $user[0];

// Sacamos los recintos de la cadena
$count = $rec->contarRecintos($id);
$listaRec = $rec->getRecintos($id);

$i = 0;

?>
<link href="default.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1>Gastro</h1>
	</div>
	<div id="search">
		Bienvenido, <? echo $nombre; ?>. <br> <a href="modules.php?module=messages&id=<? echo $id; ?>">Mis Mensajes</a> | <a href="../logout.php">Salir</a>.
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="content">
		<div class="post">
			<div class="entry">
			<p>
	 			<div id="wrapper">
			        <ul id="tabs">
        			    <!-- Tabs go here -->
       				 </ul>
       				 <div id="content">
       					<!-- Contenido -->    
       				 </div>

    				</div>
			</p>
			</div>
		</div>
	</div>

	<!-- end content -->
	<!-- start sidebar -->
	<div id="sidebar">
		<div id="doclist">
	        <h2>Menu Pedidos</h2>
	
	        <ul id="browser" class="treeview">
				<li><a href="#" rel="Activos" title="modules.php?module=activos&id=<? echo $id; ?>">Activos</a></li>	
				<li><a href="#" rel="Enviados" title="modules.php?module=enviados&id=<? echo $id; ?>">Enviados</a></li>
				<li><a href="#" rel="Respondidos" title="modules.php?module=respondidos&id=<? echo $id; ?>">Respondidos</a></li>
				<li><a href="#" rel="Opciones" title="modules.php?module=opciones&id=<? echo $id; ?>">Opciones</a></li>
				<li><a href="#" rel="Pedidos Respondidos" title="modules.php?module=opciones&id=<? echo $id; ?>">Pedidos Respondidos</a></li>
        	</ul>
		<script type="text/javascript">
			ddtreemenu.createTree("browser", true);
		</script>
    		</div>
	</div>
	<!-- end sidebar -->
	<div style="clear: both;">&nbsp;</div>
</div>
<!-- end page -->
<!-- start footer -->
<div id="footer">
	<p>&copy;2010 Todos los derechos reservados.</p>
</div>


</body>
</html>
