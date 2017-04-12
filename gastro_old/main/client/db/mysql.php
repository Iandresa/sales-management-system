<?
/* Clase MySQL
// Por Sebasti�n Arancibia (http://www.shikon.cl/proyectos) 
//
// Esta clase provee definiciones de m�todos que afectan a la base de datos del sistema.
*/

class mysql
{

// Par�metros de la Base de Datos
private $dbname;
private $dbuser;
private $dbpass;
private $link;
private $res;
private $res2;
private $host;

// Conexi�n a la Base de Datos

public function __construct($host = 'localhost',$dbuser = "iandr3_iandr3",$dbpass = "IANdresa21",$dbname = "iandr3_ristoMain",$debug=0)
{
	$this->host = $host;
	$this->dbuser = $dbuser;
	$this->dbpass = $dbpass;
	$this->dbname = $dbname;
	$this->connect();
}

function connect()
{
	$this->link = mysql_connect('localhost',$this->dbuser,$this->dbpass) or die("Error de conexion a DB");
	@mysql_select_db($this->dbname,$this->link) or die("Error de seleccion de DB");
}

// Inicio de consultas a la Base de Datos
// Consulta simple, devuelve el id resource de la consulta (luego debe ser procesada).

function query($sql)
{
	if($this->res = mysql_query($sql))
	{
		return $this->res;
	}
	else
	{
		return mysql_error();
	}
}

// Consulta simple, devuelve un arreglo asociativo con el primer campo de la Base de Datos

function queryArray($sql)
{
	if($this->res = mysql_query($sql))
	{
		return mysql_fetch_array($this->res);
	}
	else
	{
		return mysql_error();
	}
}

// Funcion para enumerar resultados (se obtiene a traves del metodo $this->query();)

function numRows()
{
	$num = mysql_num_rows($this->res);
	return $num;
}

// Conteo de datos de una BD
function contar($sql)
{
	$res = mysql_query($sql);
	$resultado = mysql_fetch_row($res);
	return $resultado[0];  
}


// Consulta m�ltiple (devuelve una arreglo de resultados, como queryArray() pero con todos los resultados)
function queryTotal($sql)
{
	$i = 0;
	$j = 0;
	$this->res = mysql_query($sql);
	$campos = mysql_num_fields($this->res);
		
		while($rsl = mysql_fetch_array($this->res))
		{
			$j = 0;
			while($j < $campos)
			{
				$result[$i][$j] = $rsl[$j];		
				$j++;
			}
			$i++;
		}
	
	if($i == 0)
	{
		// No hay resultados, salimos
		return 0;
	}
	else
	{
		return $result;
	}
}

// Moverse al proximo registro (cuando se usa query())

function moveNext()
{
	$this->res2 = mysql_fetch_array($this->res);
	$status = is_array($this->res2);
	
	return ($status);
}

// Obtener un determinado campo del resultado de la consulta (cuando se usa query()).

function getField($campo)
{
	return $this->res2[$campo];
}


}
?>
