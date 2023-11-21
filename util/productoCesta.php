<?php
class productoCesta {
    public int $id_producto;
    public string $nombreProducto;
    public float $precio;
    public string $imagen;
    public int $cantidad;

    function __construct($id_producto, $nombreProducto, $precio, 
    $imagen, $cantidad) {

        $this -> idProducto = $id_producto;
        $this -> nombreProducto = $nombreProducto;
        $this -> precio = $precio;
        $this -> imagen = $imagen;
        $this -> cantidad = $cantidad;
    }
}
?>