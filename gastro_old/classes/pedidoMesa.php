<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pedidoMesa
 *
 * @author Watanuki
 */
class pedidoMesa {

    protected  $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevoPedidoMesa($fecha,$idPedido,$idProducto,$valorNeto,$estado,$mesa,$idRecinto)
    {
        $query = "INSERT INTO pedidoMesa(fecha,idPedido,idProducto,valorNeto,estado,mesa,idRecinto) VALUES(".$fecha.",".$idPedido.",".$idProducto.",".$valorNeto.",'".$estado."',".$mesa.",".$idRecinto.");";

        if($this->db->query($query))
        {
            return "Pedido a mesa añadido correctamente";
        }
        else
        {
            return "Error añadiendo pedido a mesa";
        }
    }



    function anularPedido($id,$fecha)
    {
        $query = "UPDATE pedidoMesa SET estado = 'Anulado', fecha = ".$fecha." WHERE idPedido = ".$id.";";

        if($this->db->query($query))
        {
            return "Pedido a mesa anulado correctamente";
        }
        else
        {
            return "Error anulando pedido a mesa";
        }
    }
}
?>
