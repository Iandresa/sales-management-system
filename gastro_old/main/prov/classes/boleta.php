<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of boleta
 *
 * @author Watanuki
 */
class boleta {


   protected $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevaBoleta($idRec,$idPed,$impuesto,$descuento,$observaciones,$fecha,$estado,$totalNeto)
    {
        $query = "INSERT INTO Boleta(idRecinto,idPedido,impuesto,descuento,observaciones,fechaEmision,estado,total) VALUES(".$idRec.",".$idPed.",".$impuesto.",".$descuento.",'".$observaciones."',".$fecha.",'Emitida',".$totalNeto.");";

	echo $query;

        if($this->db->query($query))
        {
            return "Boleta generada correctamente";
        }
        else
        {
            return "Error añadiendo boleta";
        }
    }
	

	

	function searchBoletaById($id)
	{
		$query = "SELECT id,idPedido,impuesto,descuento,total,fechaEmision,estado FROM Boleta WHERE id = ".$id.";";

		return $this->db->queryArray($query);
	}



    function anularBoleta($id)
    {
        $query = "UPDATE Boleta SET estado = 'Anulado' WHERE id = ".$id.";";

        if($this->db->query($query))
        {
            return "Boleta anulada correctamente";
        }
        else
        {
            return "Error anulando boleta";
        }
    }

	function getLastIdPed()
	{
		$query = "SELECT idPedido FROM Boleta ORDER BY idPedido DESC LIMIT 0,1";

		$res = $this->db->queryArray($query);

		return $res[0];
	}

	function searchBoletasByDate($desde,$hasta,$id)
	{
		$query = "SELECT id, fechaEmision,total,estado FROM Boleta WHERE idRecinto = ".$id." AND fechaEmision < ".$hasta." AND fechaEmision > ".$desde.";";

		$res = $this->db->queryTotal($query);

		return $res;
	}

	function searchLatest($id)
	{
		$query = "SELECT id,fechaEmision,total,estado FROM Boleta WHERE idRecinto = ".$id." ORDER BY fechaEmision DESC LIMIT 0,10";

		$res = $this->db->queryTotal($query);

		return $res;
	}

	function sumaIngresos($id)
	{
		$query = "SELECT SUM(total) FROM Boleta WHERE idRecinto = ".$id." AND estado = 'Emitida'";

		$res = $this->db->queryArray($query);

		return $res[0];
	}

	function ingresosPorFecha($desde,$hasta,$id)
	{
		$query = "SELECT SUM(total) FROM Boleta WHERE idRecinto = ".$id." AND fechaEmision > ".$desde." AND fechaEmision < ".$hasta." AND estado = 'Emitida';";

		$res = $this->db->queryArray($query);

		return $res[0];
	}

	// Con los siguientes dos métodos podremos sacar la fecha de emisión de la primera boleta, util para determinar el mes en el cual podemos leer estadísticas.
	function primeraBoleta($id)
	{
		$query = "SELECT fechaEmision FROM Boleta WHERE idRecinto = ".$id." ORDER BY fechaEmision ASC LIMIT 0,1;";

		$res = $this->db->queryArray($query);

		return $res[0];
	}

	function ultimaBoleta($id)
	{
		$query = "SELECT fechaEmision FROM Boleta WHERE idRecinto = ".$id." ORDER BY fechaEmision DESC LIMIT 0,1;";

		$res = $this->db->queryArray($query);

		return $res[0];
	}

	function getIngresos($desde,$hasta,$id)
	{
		$query = "SELECT total,fechaEmision FROM Boleta WHERE idRecinto = ".$id." AND estado = 'Emitida' AND fechaEmision > ".$desde." AND fechaEmision < ".$hasta.";";

		$res = $this->db->queryTotal($query);

		return $res;
	}

}
?>
