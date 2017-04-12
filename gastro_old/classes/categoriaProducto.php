<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of categoriaProducto
 *
 * @author Watanuki
 */
class categoriaProducto {

    protected $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevaCategoriaProducto($nombre)
    {
        $query = "INSERT INTO categoriaProducto(nombre) VALUES('".$nombre."');";

        if($this->db->query($query))
        {
            return "Categoría añadida correctamente";
        }
        else
        {
            return "Error añadiendo categoría";
        }
    }

    function editarProducto($id,$nombre)
    {
        $query = "UPDATE categoriaProducto SET  nombre = '".$nombre."' WHERE id = ".$id.";";

        if($this->db->query($query))
        {
            return "Categoría modificada correctamente";
        }
        else
        {
            return "Error modificando categoría";
        }
    }


}
?>
