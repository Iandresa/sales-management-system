<?
include("db/mysql.php");
include("classes/all.php");
include("funciones.php");

$rec = new recinto();
$enc = new encargo();
$prov = new proveedor();

echo $rec->contarRecintos(12345678);
?>
