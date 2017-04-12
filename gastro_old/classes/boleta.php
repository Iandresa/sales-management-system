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

    function nuevaBoleta($id,$impuesto,$descuento,$observaciones,$fecha,$estado,$totalNeto)
    {
        $query = "INSERT INTO Boleta(id,impuesto,descuento,observaciones,fecha,estado,totalNeto) VALUES(".$id.",".$impuesto.",".$descuento.",'".$observaciones."',".$fecha.",'".$estado."',".$valorNeto.");";

        if($this->db->query($query))
        {
            return "Boleta generada correctamente";
        }
        else
        {
            return "Error añadiendo boleta";
        }
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
}
?>
