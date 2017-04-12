<?
session_start();
?>
<html>
<head>

<link href="main.css" rel="stylesheet" type="text/css" media="screen" />
<?

// Archivo de módulos del sistema. (Version Cliente)
// Para agregar un módulo nuevo, simplemente añadirlo al final del archivo.

include("db/mysql.php");
include("funciones.php");

$rec = new recinto();
$enc = new encargo();
$prov = new proveedor();
$pm = new pedidoMesa();
$bol = new boleta();
$fact = new factura();

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
	$nomRec = getNameRecinto($id);

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
		<b>Todos los datos son obligatorios!</b>
	</div>
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
		<div id="fieldDesc">Capital Inicial: </div>
		<div id="fieldData"><input type="text" name="capital" value="0"><i>Nota: Puedes modificarlo despues en el módulo de Ingresos</i></div>
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

		// Validación
		$err = 0;
		if($_POST['name'] == '' || !isset($_POST['name']))
		{
			$err = 1;
		}
		if($_POST['dir'] == '' || !isset($_POST['dir']))
		{
			$err = 1;
		}
		if($_POST['city'] == '' || !isset($_POST['city']))
		{
			$err = 1;
		}
		
		$now = ahora();
		$fono = intval($_POST['fono']);
		$year = intval($_POST['year']);

		if($fono == '' || !isset($fono))
		{
			$err = 1;
		}

		if($year < 1900 || $year > 2012 || !isset($year))
		{
			$err = 2;
		}

		if($_POST['mail'] == '' || !isset($_POST['mail']))
		{
			$err = 1;
		}

		if($_POST['pais'] == '' || !isset($_POST['pais']))
		{
			$err = 1;
		}
		if($_POST['capital'] == '' || !isset($_POST['capital']))
		{
			$_POST['capital'] = 0;
		}

		if($err == 0)
		{
			$rec->nuevoRecinto($id,$_POST['name'],$_POST['dir'],$_POST['city'],'Activo',$year,$_POST['mail'],$fono,$_POST['pais'],$now,$_POST['capital']);

			?>
			<script type="text/javascript">alert("Registro Exitoso!");</script>
		
			<a href="index.php?id=<? echo $id; ?>" target="_parent">Volver. Actualizar sistema.</a>		
		<?
		}
		if($err >= 1)
		{
			?>
			<script type="text/javascript">alert("Error ingresando recinto. Ingrese los datos correctamente");</script> <a href="javascript:history.go(-1);">Volver atrás</a>.	
			<?
		}
	}
}

// Modulo de Ingresos
if($_GET['module'] == 'ingreso')
{
		$id = $_GET['id'];
		?>
		<a href="modules.php?module=ingreso&id=<? echo $id; ?>&mode=capInicial">Capital Inicial Recinto</a> | <a href="modules.php?module=ingreso&id=<? echo $id; ?>&mode=newBoleta">Emitir Boleta</a> | <a href="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores">Consultar Boletas</a> <? /* | <a href="modules.php?module=ingreso&id=<? echo $id; ?>&mode=searchBoleta">Ver Ingresos Anteriores</a> */ ?>
		<?
	if($_GET['mode'] == 'capInicial')
	{
		$id = $_GET['id'];

		$capIni = getCapIn($id);

		if(!isset($_GET['opt']))
		{		
?>
		<h1>Ingreso Capital Inicial</h1>
		<div id="newData"><form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=capInicial&opt=add" method="post">
		Capital Inicial: <input type="text" name="capital" value="<? echo $capIni; ?>">
		</div>
		<div id="newData"><input type="submit" value=" Ingresar "></div>&nbsp;&nbsp;
		<p>Nota: Puedes cambiar el capital inicial del recinto en cualquier momento.</p>
		<?
		}
		if($_GET['opt'] == 'add')
		{
			if(preg_match('`^[0-9]+$`',$_POST['capital']))
			{
				$cap = intval($_POST['capital']);
				camCapIn($id,$cap);
				?>
				<br>Capital modificado correctamente.
				<?
			}
			else
			{
				?>
				<br>Error! Ingrese solo digitos!. <a href="javascript:history.go(-1)">Volver.</a>
				<?
			}
		}
	}

	if($_GET['mode'] == 'newBoleta')
	{
		$id = $_GET['id'];
		?>
		<h1>Ingreso de Nueva Boleta</h1>
		<div id="newData"><form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=genBoleta" method="post">
		IDMesa: <input type="text" name="mesa">
		</div>
		<div id="newData">
			<div id="cantList">Cant: </div><div id="prodList">Nombre Producto: </div><div id="valueList">$$ Unit.: </div>
		</div>
		<div id="newData">
			<div id="cantList">&nbsp;</div><div id="prodList">&nbsp;</div><div id="valueList">&nbsp;</div>
		</div>
		
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
		$err = 0;
		$id = $_GET['id'];
		$mesa = $_POST['mesa'];
		$disc = $_POST['discount'];
		$obs = $_POST['obs'];

		if($disc == '')
			$disc = '0';

		if(!preg_match('`^[0-9]+$`',$disc))
			$err = 1;

		$idP = $bol->getLastIdPed();

		if($idP == 0 || !isset($idP))
		{
			$idP = 1;
		}
		else
		{
			$idP++;
		}

		$i = 0;

		$totalNeto = 0;

		// Registramos el pedido de la mesa(version beta)

		while(isset($_POST['cant'][$i]) && intval($_POST['cant'][$i]) >= 1 && $err != 1)
		{
			$fecha = ahora();
			sleep(1);
			$cant = $_POST['cant'][$i];
			$nombre = $_POST['name'][$i];
			$precio = $_POST['value'][$i];
			$descuento = $_POST['discount'][$i];

			

			$totalNeto = intval($precio)*intval($cant) + $totalNeto;


			$pm->nuevoPedidoMesa($cant,$nombre,$fecha,$idP,$precio);

			$i++;
		}

		if($err != 1)
		{
		$totalNeto = $totalNeto;

		// Ahora escribimos en la BD los datos de la boleta.

		$bol->nuevaBoleta($id,$idP,$mesa,19,$disc,$obs,$fecha,'Normal',$totalNeto);

		echo "Boleta ingresada correctamente";
		}
		if($err == 1)
		{
			?>
			<br>Error Ingresando el descuento. Ingrese solo numeros. <a href="javascript:history.go(-1)">Volver atras</a>
			<?
		}
	}

	if($_GET['mode'] == 'searchBoleta')
	{
		
	}

	if($_GET['mode'] == 'viewAnteriores')
	{
		$id = $_GET['id'];
		?>
		Consultar por: <br>
		ID: <form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores&showBy=id" method="POST"><input type="text" name="idb" size="4"> <input type="submit" value=" Buscar "></form><br>
		Rango Fechas: <form action="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores&showBy=date" method="POST"><input type="text" name="desde" size="8" value="DD-MM-AAAA"> - <input type="text" name="hasta" size="8" value="DD-MM-AAAA"> <input type="submit" value=" Buscar "></form><br>
		<?

		if($_GET['showBy'] == 'id')
		{
			$idB = $_POST['idb'];

			if(isset($_GET['idb']))
			{
				$idB = $_GET['idb'];
			}

			$res = $bol->searchBoletaById($idB);

			$idB = $res[1];
			$idP = $res[0];
			$imp = $res[2];
			$desc = $res[3];
			$tot = $res[4];
			$fecha = $res[5];
			$est = $res[6];		

			$totalNeto = intval($tot) - (floatval($imp)/100)*intval($tot); 	

			$det = $pm->showDetail($idP);

			$i = 0;

			while(isset($det[$i][0]) || $det[$i][0] > 0)
			{
				$cant[$i] = $det[$i][0];
				$nombreProd[$i] = $det[$i][1];
				$valorNeto[$i] = $det[$i][4];
				$i++;
			}	

			// Mostrar Resultado
			?>			
			<div id="newData">
				<div id="labelBol">Número Boleta: </div><div id="dataBol"><? echo $idB; ?></div>
			</div>
			<div id="newData">
				<div id="labelBol">Descuento: </div><div id="dataBol"><? echo $desc; ?></div>
			</div>
						<div id="newData">
				<div id="labelBol">Valor Neto (sin IVA): </div><div id="dataBol"><? echo $totalNeto; ?></div>
			</div>
						<div id="newData">
				<div id="labelBol">Valor Total: </div><div id="dataBol"><? echo $tot; ?></div>
			</div>
			<div id="newData">
				<div id="labelBol">Fecha: </div><div id="dataBol"><? echo date('d-m-Y',$fecha); ?></div>
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
			$hasta = strtotime($hasta." 23:59");

			$res = $bol->searchBoletasByDate($desde,$hasta,$id);

			// Mostrar Resultados.

			?>
			<div id="newData"><b>
				<div id="idLabel">ID Bol</div><div id="fechaLabel">Fecha</div><div id="totalLabel">$$ Total</div><div id="estadoLabel">Estado</div></b>
			</div>
			<?

			$num = sizeof($res);

			$i = 0;
			while($i < $num)
			{
				if($res[$i][1] != 0)
				{
					$fecha = date("d-m-Y",$res[$i][1]);
				}
			
			?>
			<div id="newData"><a href="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores&showBy=id&idb=<? echo $res[$i][0]; ?>">
				<div id="idLabel"><? echo $res[$i][0]; ?></div><div id="fechaLabel"><? echo $fecha; ?></div><div id="totalLabel"><? echo $res[$i][2]; ?></div><div id="estadoLabel"><? echo $res[$i][3]; ?></div></a>
			</div>

			<?
		
			$i++;
			}


		}

		else if(!isset($_GET['showBy']))
		{
			$res = $bol->searchLatest($id);
			
			?><h1>Resumen:</h1><?
			$totalIngresos = $bol->sumaIngresos($id);
			?>
			<div id="newData">
			<?
			echo "Ingresos totales desde la apertura del recinto: ".$totalIngresos."\n";
			// Mostrar los ultimos resultados.

			?>
			</div>
			<div id="newData"><b>
				<div id="idLabel">ID Bol</div>
				<div id="fechaLabel">Fecha</div>
				<div id="totalLabel">$$ Total</div>
				<div id="estadoLabel">Estado</div></b>
			</div>
			<?
			
			$todos = sizeof($res);			
			
			$i = 0;

			while($i < $todos)
			{
				if($res[$i][1] != 0)
			{
				?>
			<div id="newData"><a href="modules.php?module=ingreso&id=<? echo $id; ?>&mode=viewAnteriores&showBy=id&idb=<? echo $res[$i][0]; ?>">
				<div id="idLabel"><? echo $res[$i][0]; ?></div>
				<div id="fechaLabel"><? echo date("d-m-Y",$res[$i][1]); ?></div>
				<div id="totalLabel"><? echo $res[$i][2]; ?></div>
				<div id="estadoLabel"><? echo $res[$i][3]; ?></div></a>
			</div>

			<?
			}
			$i++;
			}
		}
	}

}

if($_GET['module'] == 'gasto')
{
		$id = $_GET['id'];
		?>
		<a href="modules.php?module=gasto&id=<? echo $id; ?>&mode=newGasto">Registrar Gasto</a> | <a href="modules.php?module=gasto&id=<? echo $id; ?>&mode=viewAnteriores">Ver Gastos Anteriores</a> <? /*| <a href="modules.php?module=gasto&id=<? echo $id; ?>&mode=confirmFact">Confirmar Factura</a> */ ?>
		<?

	if($_GET['mode'] == 'newGasto')
	{
		$id = $_GET['id'];
		?>
		<h1>Ingreso de Nuevo Gasto</h1>
		<div id="newData">
			<div id="idLabel">N° Factura: </div>
			<div id="fecha2">Tipo Gasto: </div
			><div id="idLabel">Total: </div>
		</div>
		<div id="newData">
			<div id="cantList">&nbsp;</div><div id="prodList">&nbsp;</div><div id="valueList">&nbsp;</div>
		</div>
		<form action="modules.php?module=gasto&id=<? echo $id; ?>&mode=genFactura" method="post">
		<?
		for($i = 0; $i < 8; $i++)
		{
			?>
			<div id="newData">
				<div id="idLabel"><input type="text" name="doc[]"></div>
				<div id="fecha2"><input type="text" name="tipo[]"></div>
				<div id="idLabel"><input type="text" name="value[]"></div>
			</div>
			<?
		}
		?>
		
		<input type="submit" value=" Generar "></form>
		<?
	}

	if($_GET['mode'] == 'genFactura')
	{
		$id = $_GET['id'];

		$fecha = ahora();

		$i = 0;
		while(isset($_POST['doc'][$i]))
		{
			$doc = $_POST['doc'][$i];
			$tipo = $_POST['tipo'][$i];
			$precio = $_POST['value'][$i];

			$fact->nuevaFactura($id,$doc,$tipo,$precio,19,$fecha,0);

			$i++;
		}


		echo "Factura ingresada correctamente";
	}

	if($_GET['mode'] == 'viewAnteriores')
	{
		$id = $_GET['id'];
		?>
		Consultar por: <br>
		Numero: <form action="modules.php?module=gasto&id=<? echo $id; ?>&mode=viewAnteriores&showBy=id" method="POST"><input type="text" name="idf" size="4"> <input type="submit" value=" Buscar "></form>
		Rango Fechas: <form action="modules.php?module=gasto&id=<? echo $id; ?>&mode=viewAnteriores&showBy=date" method="POST"><input type="text" name="desde" size="8" value="DD-MM-AAAA"> - <input type="text" name="hasta" size="8" value="DD-MM-AAAA"> <input type="submit" value=" Buscar "></form>
		<?

		if($_GET['showBy'] == 'id')
		{
			$idf = $_POST['idf'];

			if(isset($_GET['idf']))
			{
				$idf = $_GET['idf'];
			}

			$res = $fact->searchFacturaById($idf);

			$id = $res[0];
			$idD = $res[1];
			$tipoGasto = $res[2];
			$valorTotal = $res[3];
			$idPedido = $res[4];
			$impuesto = $res[5];
			$fecha = $res[6];
			$fechaRespuesta = "No disponible";		

			$totalNeto = intval($valorTotal) - (floatval($impuesto)/100)*intval($valorTotal); 	

			$det = $enc->showDetail($idPedido);

			print_r($det);

			$i = 0;
			echo "<h2>Detalle:</h2>";

			if($det[0][1] > 0)
			{

				$idEncargo = $det[0][0];	
				$fechaRealizacion = $det[0][1];
				$fechaRespuesta = $det[0][6];
				$fechaRespuesta = date('d-m-Y H:i',$fechaRespuesta);
				$proveedor = $prov->getName($det[0][2]);

				while(isset($det[$i][0]) || $det[$i][0] > 0)
				{
					$nombreProd[$i] = $det[$i][3];
					$cantProd[$i] = $det[$i][4];
					$descProd[$i] = $det[$i][5];
					$valorProd[$i] = $det[$i][7];
					$i++;
				}
			}	

				// Mostrar Resultado
				?>			
				<div id="newData">
					<div id="labelBol">Número Factura: </div><div id="dataBol"><? echo $idD; ?></div>
				</div>
				<div id="newData">
					<div id="labelBol">Valor Neto (sin IVA): </div><div id="dataBol"><? echo $totalNeto; ?></div>
				</div>
				<div id="newData">
					<div id="labelBol">Valor Total: </div><div id="dataBol"><? echo $valorTotal; ?></div>
				</div>
				<div id="newData">
					<div id="labelBol">Fecha Realizacion: </div><div id="dataBol"><? echo date('d-m-Y H:i',$fecha); ?></div>
				</div>
				<div id="newData">
					<div id="labelBol">Fecha Respuesta: </div><div id="dataBol"><? echo $fechaRespuesta; ?></div>
				</div>
<br><br>
				<div id="newData"><b>
					<div id="label3">Cant: </div><div id="label4">Nombre:</div><div id="label5">Valor Neto:</div>
				</b></div><br><br>
				<?	

			if($det[0][1] > 0)
			{
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
			else if($det[0][1] < 0 || !isset($det[0][1]))
			{
				echo "No disponible";
			}
		}

		if($_GET['showBy'] == 'date')
		{
			$desde = $_POST['desde'];
			$hasta = $_POST['hasta'];

			$desde = strtotime($desde);
			$hasta = strtotime($hasta." 23:59");

			$res = $fact->searchFactByDate($desde,$hasta,$id);
			
			// Mostrar Resultados.

			?>
			<div id="newData"><b>
				<div id="idLabel">ID Fact</div><div id="fechaLabel">Tipo Gasto</div><div id="totalLabel">$$ Total</div><div id="estadoLabel">Fecha</div></b>
			</div>
			<?

			$num = sizeof($res);

			$i = 0;
			while($i < $num)
			{
				if($res[$i][1] != 0)
				{
					$fecha = date("d-m-Y",$res[$i][5]);
				}
			?>
			<div id="newData"><a href="modules.php?module=gasto&id=<? echo $id; ?>&mode=viewAnteriores&showBy=id&idf=<? echo $res[$i][0]; ?>">
				<div id="idLabel"><? echo $res[$i][1]; ?></div><div id="fechaLabel"><? echo $res[$i][2]; ?></div><div id="totalLabel"><? echo $res[$i][3]; ?></div><div id="estadoLabel"><? echo $fecha; ?></div></a>
			</div>

			<?
	
			$i++;
			}



		}

		else if(!isset($_GET['showBy']))
		{
			$res = $fact->searchLatest($id);
			
			?><h1>Resumen:</h1><?
			$totalIngresos = $fact->sumaIngresos($id);
			?>
			<div id="newData">
			<?
			echo "Gastos totales desde la apertura del recinto: ".$totalIngresos."\n";
			// Mostrar los ultimos resultados.

			?>
			</div>
			<div id="newData"><b>
				<div id="idLabel">ID Fact</div>
				<div id="fechaLabel">Tipo Fact</div>
				<div id="totalLabel">$$ Total</div>
				<div id="estadoLabel">Fecha</div></b>
			</div>
			<?
			
			$todos = sizeof($res);			
			
			$i = 0;

			while($i < $todos)
			{
				?>
			<div id="newData"><a href="modules.php?module=gasto&id=<? echo $id; ?>&mode=viewAnteriores&showBy=id&idf=<? echo $res[$i][0]; ?>">
				<div id="idLabel"><? echo $res[$i][1]; ?></div>
				<div id="fechaLabel"><? echo $res[$i][2]; ?></div>
				<div id="totalLabel"><? echo $res[$i][3]; ?></div>
				<div id="estadoLabel"><? echo date("d-m-Y",$res[$i][5]); ?></div></a>
			</div>

			<?
			$i++;
			}
		}
	}
}

if($_GET['module'] == 'balance')
{
	$id = $_GET['id'];

	$capIn = getCapIn($id);
	// Mostramos balance general
	if(!isset($_GET['mode']))
	{
	echo "<h2>Balance del Recinto</h2>\n";
	$capIn = getCapIn($id);
	$totIng = intval($bol->sumaIngresos($id)) + $capIn;
	$totGst = intval($fact->sumaIngresos($id));

	if($totIng >= $totGst)
	{
		$style = "color:black;"; 
		$sign = "";
		$tot = $totIng - $totGst;
	}
	if($totIng < $totGst)
	{
		$style = "color:red;";
		$sign = "-";
		$tot = $totGst - $totIng;
	}	

	?>Balance al día <? echo date("d-m-Y H:i"); ?><br><br>
	<b><div style="<? echo $style; ?>"><? echo $sign; ?> $<? echo $tot; ?></div></b><br><br>
	Ingresos: Boletas: <? echo $totIng - $capIn; ?> | Cap. Inicial: <? echo $capIn; ?><br>
	Gastos: <? echo $totGst; ?><br><br>
	<? } ?>
	Ver por: Rango de Fechas: <form action="modules.php?module=balance&id=<? echo $id; ?>&mode=porFecha" method="POST"><input type="text" name="desde" size="8" value="DD-MM-AAAA"> - <input type="text" name="hasta" size="8" value="DD-MM-AAAA"> <input type="submit" value=" Buscar "></form>
	Por año: <form action="modules.php?module=balance&id=<? echo $id; ?>&mode=porAnyo" method="POST"><input type="text" name="anyo" size="5" value="AAAA"> <input type="submit" value=" Buscar "></form>
	<?
	if($_GET['mode'] == 'porFecha')
	{
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];

		$desde = strtotime($desde);
		$hasta = strtotime($hasta." 23:59");	

		$ingresos = $bol->getIngresos($desde,$hasta,$id);
		$gastos = $fact->getGastos($desde,$hasta,$id);

		$cantIng = sizeof($ingresos);
		$cantGst = sizeof($gastos);

			
		?><h2>Balance por rango de fecha indicada:</h2>
		<div id="newData">
			<div id="ingresos">
				<div id="ing1">Fecha</div>
				<div id="ing1">Total</div>
			</div>
			<div id="gastos">
				<div id="gst2">ID Doc</div>
				<div id="gst1">Tipo Gasto</div>
				<div id="gst1">Fecha</div>
				<div id="gst2">Total</div>
			</div>
		</div>
		<?
		$i = 0;

		$totalIng = 0 + $capIn;
		$totalGst = 0;

		?>
		<div id="newData">
			<div id="ingresos">
				<?
				while($i < $cantIng)
				{
					if($res[$i][1] != 0)
				{
					$fecha = date("d-m-Y",$ingresos[$i][1]);
				}
				?>
				<div id="ing1"><? echo $fecha; ?></div>
				<div id="ing1"><? echo $ingresos[$i][0]; ?></div>
				<?
				$totalIng = $totalIng + intval($ingresos[$i][0]);
			
				$i++;
				}				
				?>			
			</div>
			<div id="gastos">
				<? 
				$j = 0;
				while($j < $cantGst)
				{
					if($res[$i][1] != 0)
				{
					$fecha = date("d-m-Y",$gastos[$j][3]);
				}
				?>
				<div id="gst2"><? echo $gastos[$j][0]; ?></div>
				<div id="gst1"><? echo $gastos[$j][1]; ?></div>
				<div id="gst1"><? echo $fecha; ?></div>
				<div id="gst2"><? echo $gastos[$j][2]; ?></div>
				<?
				$totalGst = $totalGst + intval($gastos[$j][2]);
			
				$j++;
				}
				?>
			</div>
		</div>
		<p><b>Balance en el período: </b><br><br>
		Total Ingresos: $<? echo $totalIng; ?> (Cap Inicial: <? echo $capIn; ?>)<br>
		Total Gastos: $<? echo $totalGst; ?><br><br>
		Balance: <?
		if($totalIng >= $totalGst)
		{
			$style = "color:black;"; 
			$sign = "";
			$tot = $totalIng - $totalGst;
		}
		if($totalIng < $totalGst)
		{
			$style = "color:red;";
			$sign = "-";
			$tot = $totalGst - $totalIng;
		}	
?>
	<b><div style="<? echo $style; ?>"><? echo $sign; ?> $<? echo $tot; ?></div></b>
		
		
		<?
	}

	if($_GET['mode'] == 'porAnyo')
	{
		$anyo = $_POST['anyo'];

		$desde = strtotime("01-01-".$anyo." 00:00");
		$hasta = strtotime("31-12-".$anyo." 23:59");

		$dataIng = $bol->getIngresos($desde,$hasta,$id);
		$dataGst = $fact->getGastos($desde,$hasta,$id);

		$cantIng = sizeof($dataIng);
		$cantGst = sizeof($dataGst);
			
		?><h2>Balance por año indicado: <? echo $anyo; ?></h2>
		<div id="newData">
			<div id="ingresos">
				<div id="newData">
					<b>Ingresos:</b>
				</div>
				<div id="newData">
					<div id="ing1">Fecha</div>
					<div id="ing1">Total</div>
				</div>
			</div>
			<div id="gastos">
				<div id="newData">
					<b>Gastos:</b>
				</div>
				<div id="newData">
					<div id="gst2">ID Doc</div>
					<div id="gst1">Tipo Gasto</div>
					<div id="gst1">Fecha</div>
					<div id="gst2">Total</div>
				</div>
			</div>
		</div>
		<?
		$i = 0;

		$totalIng = 0 + $capIn;
		$totalGst = 0;

		?>
		<div id="newData">
			<div id="ingresos">&nbsp;&nbsp;
				<?
				while($i < $cantIng)
				{
					if($res[$i][1] != 0)
				{
					$fecha = date("d-m-Y",$dataIng[$i][1]);
				}
				?>
				<div id="ing1"><? echo $fecha; ?></div>
				<div id="ing1"><? echo $dataIng[$i][0]; ?></div>
				<?
				$totalIng = $totalIng + intval($dataIng[$i][0]);
		
				$i++;
				}				
				?>			
			</div>
			<div id="gastos">&nbsp;&nbsp;
				<? 
				$j = 0;
				while($j < $cantGst)
				{
					if($res[$i][1] != 0)
				{
					$fecha = date("d-m-Y",$dataGst[$j][3]);
				}
				?>
				<div id="gst2"><? echo $dataGst[$j][0]; ?></div>
				<div id="gst1"><? echo $dataGst[$j][1]; ?></div>
				<div id="gst1"><? echo $fecha; ?></div>
				<div id="gst2"><? echo $dataGst[$j][2]; ?></div>
				<?
				$totalGst = $totalGst + intval($dataGst[$j][2]);
				
				$j++;
				}
				?>
			</div>
		</div>
		<p><b>Balance en el período: </b><br><br>
		Total Ingresos: $<? echo $totalIng; ?> (Cap. Inicial <? echo $capIn; ?>)<br>
		Total Gastos: $<? echo $totalGst; ?><br><br>
		Balance: <?
		if($totalIng >= $totalGst)
		{
			$style = "color:black;"; 
			$sign = "";
			$tot = $totalIng - $totalGst;
		}
		if($totalIng < $totalGst)
		{
			$style = "color:red;";
			$sign = "-";
			$tot = $totalGst - $totalIng;
		}	
?>
	<b><div style="<? echo $style; ?>"><? echo $sign; ?> $<? echo $tot; ?></div></b>
		
		
		<?
	}
}

if($_GET['module'] == 'datos')
{
	$id = $_GET['id'];

	if($_GET['mode'] == 'edit')
	{
		$rec->editarRecinto($_GET['id'],$_POST['nom'],$_POST['dir'],$_POST['ciu'],$_POST['mail'],$_POST['fono']);

		echo "Datos editados correctamente\n\n";
	}

	$data = getDataRecinto($id);

	$rut = $data[0];
	$nom = $data[1];
	$dir = $data[2];
	$ciu = $data[3];
	$est = $data[4];
	$mail = $data[5];
	$fono = $data[6];
	$pais = $data[7];

	?>
	<form action="modules.php?module=datos&id=<? echo $id; ?>&mode=edit" method="POST">
	<div id="newData">
		<div id="idLabel">ID: </div><div id="label4"><? echo $rut; ?></div>
	</div>
	<div id="newData">
		<div id="idLabel">Nombre: </div><div id="label4"><input type="text" name="nom" value="<? echo $nom; ?>"></div>
	</div>
	<div id="newData">
		<div id="idLabel">Direccion: </div><div id="label4"><input type="text" name="dir" value="<? echo $dir; ?>"></div>
	</div>
	<div id="newData">
		<div id="idLabel">Ciudad: </div><div id="label4"><input type="text" name="ciu" value="<? echo $ciu; ?>"></div>
	</div>
	<div id="newData">
		<div id="idLabel">Estado: </div><div id="label4"><? echo $est; ?></div>
	</div>
	<div id="newData">
		<div id="idLabel">Correo Electronico: </div><div id="label4"><input type="text" name="mail" value="<? echo $mail; ?>"></div>
	</div>
	<div id="newData">
		<div id="idLabel">Fono: </div><div id="label4"><input type="text" name="fono" value="<? echo $fono; ?>"></div>
	</div>
	<div id="newData">
		<div id="idLabel">Pais: </div><div id="label4"><input type="text" name="pais" value="<? echo $pais; ?>"></div>
	</div>
	<div id="newData">
		<div id="idLabel">&nbsp;</div><div id="label4"><input type="submit" value=" Editar Datos "></div>
	</div>
	</form>
	<?

}

if($_GET['module'] == 'messages')
{
	$id = $_GET['id'];
	// Buscamos mensajes en la BD
	$msg = getMensajes($id);		

	// Los mostramos
	?>
	<div id="newData">
		<a href="modules.php?module=messages&id=<? echo $id; ?>">Ver Mensajes Recibidos</a> | <a href="modules.php?module=messages&id=<? echo $id; ?>&mode=new">Nuevo Mensaje</a> | <a href="modules.php?module=messages&id=<? echo $id; ?>&mode=sent">Ver Mensajes Enviados</a>
	</div>
	<?
	if($_GET['mode'] == 'sent')
	{
		$msg = getMensajesEnviados($id);
	?>
	<div id="newData">
		<h2>Mensajes Enviados</h2>
	</div>
	<div id="newData"><strong>
		<div id="destData">Enviado a</div>
		<div id="msgData">Asunto</div></strong>
	</div>
	<div id="newData">&nbsp;</div>
	<?

	$cantMsg = sizeof($msg);

	$i = 0;

	while($i < $cantMsg)
	{
		$msg = getMensajes($id);

		// Buscamos el nombre del usuario destinatario
		$nameRem = searchName($msg[$i][1]);
		if($msg[$i][5] == 0)
		{
			echo "<b>";
		}
		?>
		<div id="newData"><a href="modules.php?module=messages&id=<? echo $id; ?>&showMessage=<? echo $msg[$i][0]; ?>">
			<div id="destData"><? echo $nameRem; ?></div>
			<div id="msgData"><? echo $msg[$i][3]; ?></div></a>
		</div>
		<?
		if($msg[$i][5] == 0)
		{
			echo "</b>";
		}
		$i++;
	}

	}

	if(!isset($_GET['mode']))
	{
	?>

	<div id="newData">
		<h2>Mensajes Recibidos</h2>
	</div>
	<div id="newData"><strong>
		<div id="destData">Enviado por</div>
		<div id="msgData">Asunto</div></strong>
	</div>
	<div id="newData">&nbsp;</div>
	<?

	$cantMsg = sizeof($msg);

	$i = 0;

	while($i < $cantMsg)
	{
		// Buscamos el nombre del usuario remitente
		$nameRem = searchName($msg[$i][2]);
		if($msg[$i][5] == 0)
		{
			echo "<b>";
		}
		?>
		<div id="newData"><a href="modules.php?module=messages&id=<? echo $id; ?>&showMessage=<? echo $msg[$i][0]; ?>">
			<div id="destData"><? echo $nameRem; ?></div>
			<div id="msgData"><? echo $msg[$i][3]; ?></div></a>
		</div>
		<?
		if($msg[$i][5] == 0)
		{
			echo "</b>";
		}
		$i++;
	}

	}	

	if(isset($_GET['showMessage']))
	{
		// Mostramos el mensaje
		$msg = getMensaje($_GET['showMessage']);

		marcarLeido($_GET['showMessage']);
		
		$remMsg = searchName($msg[1]);
		$asunto = $msg[3];
		$mensaje = $msg[4];
		$leido = $msg[5];
		?>
		<div id="newData">
			<div id="msg1">Remitente: </div><div id="msg2"><? echo $remMsg; ?></div>
		</div>
		<div id="newData">
			<div id="msg1">Destinatario: </div><div id="msg2"><? echo $msg[2]; ?></div>
		</div>
		<div id="newData">
			<div id="msg1">Asunto: </div><div id="msg2"><? echo $asunto; ?></div>
		</div>
		<div id="newData">
			&nbsp;
		</div>
		<div id="newData">
			<p><? echo $mensaje; ?></p>
		</div>
		<div id="newData">&nbsp;</div>
		<div id="newData"><h2>Responder</h2></div>
		<div id="newData">
			<form action="modules.php?module=messages&id=<? echo $id; ?>&mode=reply" method="post">
				<textarea name="mensaje" rows="20" cols="20">Ingrese aqui la respuesta</textarea>
				<input type="hidden" name="asunto" value="Re: <? echo $asunto; ?>">
				<input type="hidden" name="destino" value="<? echo $msg[2]; ?>">
			</form>
		</div>
		<?
	}
	// Modulo para responder mensajes
	if($_GET['mode'] == 'reply')
	{
		$asunto = $_POST['asunto'];	
		$destino = $_POST['destino'];
		$remitente = $_GET['id'];
		$mensaje = $_POST['mensaje'];

		enviarMensaje($remitente,$destino,$asunto,$mensaje);
	}

	// Modulo para crear mensajes
	if($_GET['mode'] == 'new')
	{

		echo "Pronto...";
		// Ver plugin de autocompletado.
		

		// Formulario envio mensaje,
	}
}
