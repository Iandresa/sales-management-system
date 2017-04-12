<?
session_start();
unset($_SESSION['id']);
session_destroy();

?>
Salida exitosa!
<script type="text/javascript">
alert("Ha salido del sistema");
window.location = "../index.html";
</script>
