<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of encargo
 *
 * @author Sebastián Arancibia
 */
class encargo {

    protected  $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevoEncargo($id,$fecha,$rec,$nomProducto,$cant,$desc)
    {

        $query = "INSERT INTO Encargo(idEncargo,fechaRealizacion,idProveedor,idRecinto,nombreProducto,cantidad,descripcionProducto,estadoEncargo,fechaRespuesta,precioUnitario) VALUES(".$id.",".$fecha.",0,".$rec.",'".$nomProducto."',".$cant.",'".$desc."','solicitado',0,0);";

        if($this->db->query($query))
        {
            return "Encargo añadido correctamente";
        }
        else
        {
            return "Error añadiendo encargo";
        }
    }



    function anularEncargo($id,$fecha)
    {
        $query = "UPDATE Encargo SET tipo = 'Anulado', fecha = ".$fecha." WHERE idEncargo = ".$id.";";

        if($this->db->query($query))
        {
            return "Encargo anulado correctamente";
        }
        else
        {
            return "Error anulando encargo";
        }
    }

	function getLastEnc()
	{
		$query = "SELECT idEncargo FROM Encargo ORDER BY idEncargo DESC LIMIT 0,1";

		if($this->db->query($query))
        	{
        	    return $this->db->queryArray($query);
        	}
        	else
        	{
        	    return 0;
        	}
	}

	function getDataCotiz($idPed)
	{
		$query = "SELECT * FROM Encargo WHERE idEncargo = ".$idPed.";";

		return $this->db->queryTotal($query);
	}

	function changeStatusEncargo($status,$id)
	{
		$query = "UPDATE Encargo SET estadoEncargo =  '".$status."' WHERE idPedido = ".$id.";";

		$this->db->query($query);

		echo "Su encargo ha pasado a modo :".$status.". Por favor espere la respuesta del proveedor.";
	}

	function getDataVerPed($id)
	{
		$query = "SELECT DISTINCT idEncargo,idProveedor,fechaRealizacion,estadoEncargo FROM Encargo WHERE idRecinto = ".$id." AND estadoEncargo != 'cotiz' GROUP BY idEncargo;";

		if($this->db->queryTotal($query))
		{
			return $this->db->queryTotal($query);
		}
		else
		{
			echo "No hay resultados";
			return 0;
		}
	}

	function getCotiz($id)
	{
		$query = "SELECT DISTINCT idEncargo,idProveedor,fechaRealizacion,estadoEncargo FROM Encargo WHERE idRecinto = ".$id." AND estadoEncargo = 'cotiz' OR estadoEncargo = 'mejora';";

		if($this->db->queryTotal($query))
		{
			return $this->db->queryTotal($query);
		}
		else
		{
			echo "No hay resultados";
			return 0;
		}
	}

	function countGetCotiz($id)
	{
		$query = "SELECT count(*) FROM Encargo WHERE idRecinto = ".$id." AND estadoEncargo = 'cotiz';";

		return $this->db->contar($query);
	}

	function showDetail($id)
	{
		$query = "SELECT idEncargo,fechaRealizacion,idProveedor,nombreProducto,cantidad,descripcionProducto,fechaRespuesta,precioUnitario FROM Encargo WHERE idEncargo = ".$id.";";

		$res = $this->db->queryTotal($query);

		return $res;
	}

	function searchActivos()
	{
		$query = "SELECT DISTINCT idEncargo,idRecinto FROM Encargo WHERE estadoEncargo = 'solicitado' GROUP BY idEncargo;";

		$res = $this->db->queryTotal($query);

		return $res;
	}
}
?>
