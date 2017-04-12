<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of factura
 *
 * @author Sebastián Arancibia
 */
class factura {

    protected $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevaFactura($idRecinto,$idEncargo,$nombre,$tipo,$valorNeto,$valorTotal,$observaciones,$fecha)
    {
        $query = "INSERT INTO Factura(idRecinto,idEncargo,nombre,tipo,valorNeto,valorTotal,observaciones,fecha) VALUES(".$idRecinto.",".$idEncargo.",'".$nombre."','".$tipo."',".$valorNeto.",".$valorTotal.",'".$observaciones."',".$fecha.");";

        if($this->db->query($query))
        {
            return "Factura añadida correctamente";
        }
        else
        {
            return "Error añadiendo factura";
        }
    }



    function anularFactura($id)
    {
        $query = "UPDATE Factura SET tipo = 'Anulado' WHERE id = ".$id.";";

        if($this->db->query($query))
        {
            return "Factura anulada correctamente";
        }
        else
        {
            return "Error anulando factura";
        }
    }
}
?>
