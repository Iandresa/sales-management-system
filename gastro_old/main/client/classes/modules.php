<html>
<head>

<link href="main.css" rel="stylesheet" type="text/css" media="screen" />
<?

// Archivo de módulos del sistema. (Version Cliente)
// Para agregar un módulo nuevo, simplemente añadirlo al final del archivo.

include("db/mysql.php");
include("classes/all.php");
include("funciones.php");

$rec = new recinto();
$enc = new encargo();
$prov = new proveedor();

?>
</head>
<body>
<?

if($_GET['module'] == 'opciones')
{
	$id = $_GET['id'];

	if($_GET['mode'] != 'change')
	{
		$opc = getOpciones($id);
	
		$pais = $opc[0];
		$iva = $opc[1];
		$ccif = $opc[2];
	
		?>
		<form target="Opciones" action="modules.php?module=opciones&id=<? echo $id; ?>&mode=change" method="post">
		<div id="newData">
			<div id="fieldDesc">Pais: </div>
			<div id="fieldData"><input type="text" name="pais" value="<? echo $pais; ?>"></div>
		</div>
		<div id="newData">
			<div id="fieldDesc">IVA: </div>
			<div id="fieldData"><input type="text" name="iva" size="5" value="<? echo $iva; ?>"></div>
		</div>
		<div id="newData">
			<div id="fieldDesc">Cantidad Cifras: </div>
			<div id="fieldData"><input type="text" name="ccif" size="5" value="<? echo $ccif; ?>"></div>
		</div>
		<div id="newData">
			<div id="fieldDesc">&nbsp;</div>
			<div id="fieldData"><input type="submit" name=" Guardar Cambios "></div>
		</div>
		</form>
		<?
		}

		if($_GET['mode'] == 'change')
		{
			setOpciones($_GET['id'],$_POST['pais'],$_POST['iva'],$_POST['ccif']);
			?>
			<script type="text/javascript">alert("Cambios guardados");</script>
			<?
		}
}

// Módulo de Pedidos
if($_GET['module'] == 'pedido')
{
	$id = $_GET['id'];
	$nomRec = $rec->getName($id);

	?><h1>Pedidos Recinto: <? echo $nomRec; ?></h1>
	<a href="modules.php?mode=makePedido&id=<? echo $id; ?>&module=pedido">Realizar Pedido</a> | <a href="modules.php?mode=verCotiz&id=<? echo $id; ?>&module=pedido" >Ver Cotizaciones</a> | <a href="modules.php?mode=verPedidos&id=<? echo $id; ?>&module=pedido">Ver Pedidos Realizados</a><br><br>
	<?
	if($_GET['mode'] == 'verPedidos')
	{

		if($_GET['st'] == 'show')
		{
			?><h1>Detalle de Pedido Seleccionado</h1><?
			$idPedido = $_GET['idPedido'];
			$id = $_GET['id'];

			$ped = $enc->getDataCotiz($idPedido);

			$name_prov = $prov->getName($ped[0][2]);
			$fechaPedido = date('d-m-Y H:i',$ped[0][1]);

			$count = sizeof($ped);
			?>
			<div id="newData">
				Fecha Pedido: <? echo $fechaPedido; ?>			
			</div>
			<div id="newData">
				Proveedor: <? echo $name_prov; ?>
			</div>&nbsp;
			<div id="newData"><b>
			<div id="cantList">Cant: </div><div id="prodList">Nombre Producto: </div><div id="valueList">Precio:</b> </div>
			</div>
			<div id="newData">
			<div id="cantList">&nbsp;</div><div id="prodList">&nbsp;</div><div id="valueList">&nbsp;</div></div>
			<?
			$i = 0;
			$total = 0;
			while($i < $count)
			{
				?>
				<div id="newData">
				<div id="cantList"><? echo $ped[$i][5]; ?></div><div id="prodList"><? echo $ped[$i][4]; ?> (<? echo $ped[$i][6]; ?>)</div><div id="valueList"><? echo $ped[$i][9]; ?></div></div>
				<?

				// Calculamos el total
				$total = intval($ped[$i][4])*intval($ped[$i][9]) + $total;

				$i++;
			}
			?>
			<div id="newData">&nbsp;</div>
			<div id="newData">
			<b>Total: <? echo $total; ?></b><br>
			<b>Estado: </b><? echo $ped[$i-1][7]; ?><br>
			<i>Nota: Valores incluyen IVA</i><br><br>
			</div>			
			<?
		}

	else
	{
		?><b>Listado de pedidos anteriores (ya realizados o cancelados)</b><br><br><?

		$tc = $enc->getDataVerPed($id);

		$count = sizeof($tc);

		// Si no hay primer resultado, no hay pedidos que cumplan con la condición
		if(!isset($tc[0][0]) || $tc[0][0] == 0)
		{
			echo "No hay resultados";
		}

		else
		{
			?><b>Haz clic sobre el pedido para ver el detalle</b>
			<div id="newData">
				<div id="labelBol">Estado</div><div id="labelBol">Proveedor</div><div id="fecha2">Fecha Ped</div>			</div>
			<div id="newData">&nbsp;</div>
			<?
			$i = 0;
			while($i < $count)
			{
				$prv = $tc[$i][1];

				$name_prov = $prov->getName($prv);

				$fechaPedido = date('d-m-Y H:i',$tc[$i][2]);
				?>
				<div id="newData">
					<div id="labelBol"><? echo $tc[$i][3]; ?></div><a href="modules.php?module=pedido&id=<? echo $id; ?>&mode=verPedidos&idPedido=<? echo $tc[$i][0]; ?>&st=show"><div id="labelBol"><? echo $name_prov; ?></div><div id="fecha2"><? echo $fechaPedido; ?></div></a>
				</div>
				<?
			$i++;
			}
		}
	}

		
	}	


	if($_GET['mode'] == 'makePedido' && $_GET['opt'] != 'add')
	{
	?>
		<h1>Realizar un Pedido</h1>
		<form action="modules.php?module=pedido&id=<? echo $id; ?>&mode=makePedido&opt=add" method="post">
		<div id="newData">
			<div id="cantList">Cant: </div><div id="nameList">Nombre: </div><div id="descList">Descripción: </div>
			<?
			for($i = 0; $i < 10; $i++)
			{
			?>
			<div id="cantList"><input type="text" name="cant[]"></div><div id="nameList"><input type="text" name="value[]"></div><div id="descList"><input type="text" name="desc[]"></div>
		
			<?
			}
			?>
			</div>
			<div id="newData"><p><input type="submit" value=" Realizar Pedido "></p></div>

	<?
	}

	if($_GET['mode'] == 'makePedido' && $_GET['opt'] == 'add')
	{
		?><h1>Realizar un Pedido</h1><?
		$idEncargo = $enc->getLastEnc();


		if($idEncargo[0] == 0)
		{
			$idEncargo[0] = 1;
		}
		else
		{
			$idEncargo[0]++;
		}

		for($i = 0; $i < 10; $i++)
		{
			$now = ahora();
			$cant = $_POST['cant'][$i];
			$value = $_POST['value'][$i];
			$desc = $_POST['desc'][$i];

			$enc->nuevoEncargo($idEncargo[0],$now,$id,$value,$cant,$desc);
			sleep(1);
		}

		echo "Pedido Realizado Correctamente. Cuando recibas una oferta serás notificado.";
	}

	if($_GET['mode'] == 'verCotiz')
	{
		if($_GET['st']=='show')
		{
			$idPedido = $_GET['idPedido'];
			$id = $_GET['id'];

			$ped = $enc->getDataCotiz($idPedido);
			?>
			<div id="cantList">Cant: </div><div id="prodList">Nombre Producto: </div><div id="valueList">Precio: </div>
			<div id="cantList">&nbsp;</div><div id="prodList">&nbsp;</div><div id="valueList">&nbsp;</div>
			<?
			$i = 0;
			$total = 0;
			while($i < $count)
			{
				?>
				<div id="cantList"><? echo $tc[$i][5]; ?></div><div id="prodList"><? echo $tc[$i][4]; ?></div><div id="valueList"><? echo $tc[$i][9]; ?></div>
				<?

				// Calculamos el total
				$total = intval($tc[$i][4])*intval($tc[$i][9]) + $total;

				$i++;
			}
			?>
			<br>
			<b>Total: <? echo $total; ?></b><br>
			<i>Nota: Valores incluyen IVA</i><br><br>
			<b>Opciones:</b><br><br>
			<form action="modules.php?module=pedido&id=<? echo $id; ?>&mode=verCotiz&st=def&pid=<? $tc[0][0]; ?>" method="post"
			<input type="radio" name="option" value="aceptar"> Aceptar Cotizacion<br>
			<input type="radio" name="option" value="mejora"> Pedir Mejora<br>
			<input type="radio" name="option" value="cancelar"> Rechazar Cotizacion<br>
			<input type="submit" value=" Enviar Respuesta "></form>
			<?	
		}		
		if($_GET['st'] == 'def')
		{
			$id = $_GET['pid'];
			
			$status = $_POST['option'];
			
			$enc->changeStatusEncargo($status,$id);

		}			
		
		else
		{
		?><h1>Ver Cotizaciones Realizadas por Proveedores</h1><?

		$count = $enc->countGetCotiz($id);
		$tc = $enc->getCotiz($id);
		
		// Si no hay primer resultado, no hay cotizaciones nuevas
		if(!isset($tc[0][0]) || $tc[0][0] == 0)
		{
			echo "No hay cotizaciones";
		}

		else
		{
			?><b>Haz clic sobre el pedido para ver la cotización</b>
			<div id="newData">
				<div id="provList">Proveedor</div><div id="fecha">Fecha Pedido</div>
			</div>
			<div id="newData">&nbsp;</div>
			<?
			$i = 0;
			while($i < $count)
			{
				$prv = $tc[$i][1];

				$name_prov = $prov->getName($prv);
				$fechaPedido = date('d-m-Y H:i',$tc[$i][2]);
				?>
				<div id="newData">
					<a href="modules.php?module=pedido&id=<? echo $id; ?>&mode=verCotiz&idPedido=<? echo $tc[$i][0]; ?>&st=show"><div id="provList"><? echo $name_prov; ?></div><div id="fecha"><? echo $fechaPedido; ?></div></a>
				</div>
				<?
			}
		}

		}
	}
}

// Módulo para añadir recinto. Formulario simple que añade un recinto determinado.
if($_GET['module'] == 'addRecinto')
{
	$id = $_GET['id'];
	
	if($_GET['mode'] != 'add')
	{
	?>
	<form action="modules.php?module=addRecinto&id=<? echo $id; ?>&mode=add" method="post" >
	<div id="newData">
		<div id="fieldDesc">Nombre: </div>
		<div id="fieldData"><input type="text" name="name"> <i>(Nota: Un nombre significativo para identificar el recinto).</i></div>
	</div>
	<div id="newData">
		<div id="fieldDesc">Dirección: </div>
		<div id="fieldData"><input type="text" name="dir"></div>
	</div>
	<div id="newData">
		<div id="fieldDesc">Ciudad: </div>
		<div id="fieldData"><input type="text" name="city"></div>
	</div>
	<div id="newData">
		<div id="fieldDesc">Año de Inicio: </div>
		<div id="fieldData"><input type="text" name="year" size="5"></div>
	</div>
	<div id="newData">
		<div id="fieldDesc">País: </div>
		<div id="fieldData"><input type="text" name="pais"></div>
	</div>
	<div id="newData">
		<div id="fieldDesc">Correo: </div>
		<div id="fieldData"><input type="text" name="mail"></div>
	</div>
	<div id="newData">
		<div id="fieldDesc">Fono: </div>
		<div id="fieldData"><input type="text" name="fono"></div>
	</div>
	<div id="newData">
		<div id="fieldDesc">&nbsp;</div>
		<div id="fieldData"><input type="submit" value=" Registrar "></div>
	</div>
	<div id="newData">
		<div id="fieldDesc">&nbsp;</div>
		<div id="fieldData"><i>Nota: Podrás encontrar el nuevo recinto en el menú de la izquierda</i></div>
	</div>
	<?
	}

	if($_GET['mode'] == 'add')
	{
		$now = ahora();
		$fono = intval($_POST['fono']);
		$year = intval($_POST['year']);

		$rec->nuevoRecinto($_GET['id'],$_POST['name'],$_POST['dir'],$_POST['city'],'Activo',$year,$_POST['mail'],$fono,$_POST['pais'],$now);

		?>
		<script type="text/javascript">alert("Registro Exitoso!");</script>
		<script type="text/javascript">window.location = "index.php?id="+<? echo $_GET['id']; ?>;</script>
		<?
		
	}
}

// Modulo de Ingresos
if($_GET['module'] == 'ingreso')
{
	if(!isset($_GET['mode']))
	{
		$id = $_GET['id'];
		?>
		<a href="modules.php?module=ingreso&id=<? echo $id; ?>&mode=newBoleta">Emitir Boleta</a> | <a href="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores">Ver Ingresos Anteriores</a> | <a href="modules.php?module=ingreso&id=<? echo $id; ?>&mode=searchBoleta">Consultar Boleta</a>
		<?
	}

	if($_GET['mode'] == 'newBoleta')
	{
		$id = $_GET['id'];
		?>
		<h1>Ingreso de Nueva Boleta</h1>
		<div id="newData">
			<div id="cantList">Cant: </div><div id="prodList">Nombre Producto: </div><div id="valueList">Precio: </div>
		</div>
		<div id="newData">
			<div id="cantList">&nbsp;</div><div id="prodList">&nbsp;</div><div id="valueList">&nbsp;</div>
		</div>
		<form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=genBoleta" method="post">
		<?
		for($i = 0; $i < 20; $i++)
		{
			?>
			<div id="newData">
				<div id="cantList"><input type="text" name="cant[]"></div><div id="prodList"><input type="text" name="name[]"></div><div id="valueList"><input type="text" name="value[]"></div>
			</div>
			<?
		}
		?>
		<div id="newData">
				<div id="label1">Descuento: </div><div id="label2"><input type="text" name="discount"></div>
		</div>
		<div id="newData">
				<div id="label1">Observaciones: </div><div id="label2"><input type="text" name="obs"></div>
		</div>
		<input type="submit" value=" Generar "></form>
		<?
	}

	if($_GET['mode'] == 'genBoleta')
	{
		$id = $_GET['id'];
		$disc = $_POST['discount'];
		$obs = $_POST['obs'];

		$fecha = ahora();

		$idP = getLastIdPed();

		if($idP == 0 || !isset($idP))
		{
			$idP = 1;
		}
		else
		{
			$idP++;
		}

		$i = 0;

		// Registramos el pedido de la mesa(version beta)

		while(isset($_POST['cant'][$i]))
		{
			$cant = $_POST['cant'][$i];
			$nombre = $_POST['name'][$i];
			$precio = $_POST['value'][$i];
			$descuento = $_POST['discount'][$i];


			nuevoPedidoMesa($cant,$nombre,$fecha,$idP,$precio);

			$i++;
		}

		// Ahora escribimos en la BD los datos de la boleta.

		nuevaBoleta($id,$idP,19,$descuento,$obs,$fecha,$totalNeto);

		echo "Boleta ingresada correctamente";
	}

	if($_GET['mode'] == 'viewAnteriores')
	{
		$id = $_GET['id'];
		?>
		Consultar por: <br>
		ID: <form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores&showBy=id" method="POST"><input type="text" name="idb" size="4"> <input type="submit" value=" Buscar "></form><br>
		Rango Fechas: <form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores&showBy=date" method="POST"><input type="text" name="desde" size="5" value="DD-MM-AAAA"> - <input type="text" name="hasta" size="5" value="DD-MM-AAAA"> <input type="submit" value=" Buscar "></form><br>
		<?

		if($_GET['showBy'] == 'id')
		{
			$idB = $_POST['idb'];

			if(isset($_GET['idb']))
			{
				$idB = $_GET['idb'];
			}

			$res = searchBoletaById($idB);

			$idB = $res[1];
			$idP = $res[0];
			$imp = $res[2];
			$desc = $res[3];
			$tot = $res[4];
			$fecha = $res[5];
			$est = $res[6];		

			$totalNeto = intval($tot) - (floatval($imp)/100)*intval($tot); 	

			$det = showDetail($idP);

			$i = 0;

			while(isset($det[0]) || $det[0] > 0)
			{
				$cant[$i] = $det[0];
				$nombreProd[$i] = $det[1];
				$valorNeto[$i] = $det[4];
				$i++;
			}	

			// Mostrar Resultado
			?>			
			<div id="newData">
				<div id="labelBol">Número Boleta: </div><div id="dataBol"><? echo $idB; ?></div>
			</div>
			<div id="newData">
				<div id="labelBol">Descuento: </div><div id="dataBol"><? echo $imp; ?></div>
			</div>
						<div id="newData">
				<div id="labelBol">Valor Neto (sin IVA): </div><div id="dataBol"><? echo $totalNeto; ?></div>
			</div>
						<div id="newData">
				<div id="labelBol">Valor Total: </div><div id="dataBol"><? echo $tot; ?></div>
			</div>
			<div id="newData">
				<div id="labelBol">Fecha: </div><div id="dataBol"><? echo $fecha; ?></div>
			</div>
<br><br>
			<div id="newData">
				<div id="label3">Cant: </div><div id="label4">Nombre:</div><div id="label5">Valor Neto:</div>
			</div><br><br>
			<?	
			$fin = $i;

			$i = 0;	
			while($i < $fin)
			{
				?>
			<div id="newData">
				<div id="label3"><? echo $cant[$i]; ?></div><div id="label4"><? echo $nombreProd[$i]; ?></div><div id="label5"><? echo $valorNeto[$i]; ?></div>
			</div>
				<?
			$i++;
			}

				
		}

		if($_GET['showBy'] == 'date')
		{
			$desde = $_POST['desde'];
			$hasta = $_POST['hasta'];

			$desde = strtotime($desde);
			$hasta = strtotime($hasta);

			$res = searchBoletasByDate($desde,$hasta);

			// Mostrar Resultados.

			?>


			<?


		}

		else
		{
			$res = searchLatest();

			// Mostrar los ultimos 20 resultados.
		}
	}

}

if($_GET['module'] == 'gasto')
{
	if(!isset($_GET['mode']))
	{
		$id = $_GET['id'];
		?>
		<a href="modules.php?module=gasto&id=<? echo $id; ?>&mode=newGasto">Registrar Gasto</a> | <a href="modules.php?module=gasto&id=<? echo $id; ?>&mode=viewAnteriores">Ver Gastos Anteriores</a> | <a href="modules.php?module=gasto&id=<? echo $id; ?>&mode=confirmFact">Confirmar Factura</a>
		<?
	}

	if($_GET['mode'] == 'newGasto')
	{
		$id = $_GET['id'];
		?>
		<h1>Ingreso de Nueva Boleta</h1>
		<div id="newData">
			<div id="cantList">Cant: </div><div id="prodList">Nombre Producto: </div><div id="valueList">Precio: </div>
		</div>
		<div id="newData">
			<div id="cantList">&nbsp;</div><div id="prodList">&nbsp;</div><div id="valueList">&nbsp;</div>
		</div>
		<form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=genBoleta" method="post">
		<?
		for($i = 0; $i < 20; $i++)
		{
			?>
			<div id="newData">
				<div id="cantList"><input type="text" name="cant[]"></div><div id="prodList"><input type="text" name="name[]"></div><div id="valueList"><input type="text" name="value[]"></div>
			</div>
			<?
		}
		?>
		<div id="newData">
				<div id="label1">Descuento: </div><div id="label2"><input type="text" name="discount"></div>
		</div>
		<div id="newData">
				<div id="label1">Observaciones: </div><div id="label2"><input type="text" name="obs"></div>
		</div>
		<input type="submit" value=" Generar "></form>
		<?
	}

	if($_GET['mode'] == 'genFactura')
	{
		$id = $_GET['id'];
		$disc = $_POST['discount'];
		$obs = $_POST['obs'];

		$fecha = ahora();

		$idP = getLastIdPed();

		if($idP == 0 || !isset($idP))
		{
			$idP = 1;
		}
		else
		{
			$idP++;
		}

		$i = 0;

		// Registramos el pedido de la mesa(version beta)

		while(isset($_POST['cant'][$i]))
		{
			$cant = $_POST['cant'][$i];
			$nombre = $_POST['name'][$i];
			$precio = $_POST['value'][$i];
			$descuento = $_POST['discount'][$i];


			nuevoPedidoMesa($cant,$nombre,$fecha,$idP,$precio);

			$i++;
		}

		// Ahora escribimos en la BD los datos de la boleta.

		nuevaBoleta();

		echo "Boleta ingresada correctamente";
	}

	if($_GET['mode'] == 'viewAnteriores')
	{
		$id = $_GET['id'];
		?>
		Consultar por: <br>
		ID: <form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores&showBy=id" method="POST"><input type="text" name="idb" size="4"> <input type="submit" value=" Buscar "></form><br>
		Rango Fechas: <form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores&showBy=date" method="POST"><input type="text" name="desde" size="5" value="DD-MM-AAAA"> - <input type="text" name="hasta" size="5" value="DD-MM-AAAA"> <input type="submit" value=" Buscar "></form><br>
		<?

		if($_GET['showBy'] == 'id')
		{
			$idB = $_POST['idb'];

			$res = searchBoletaById($idB);
		}

		if($_GET['showBy'] == 'date')
		{
			$desde = $_POST['desde'];
			$hasta = $_POST['hasta'];

			$desde = strtotime($desde);
			$hasta = strtotime($hasta);

			$res = searchBoletasByDate($desde,$hasta);


		}

		else
		{}
	}
}

if($_GET['module'] == 'balance')
{}

if($_GET['module'] == 'datos')
{}

if($_GET['module'] == 'messages')
{}
