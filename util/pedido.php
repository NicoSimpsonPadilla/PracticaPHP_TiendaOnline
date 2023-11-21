<?php
    require "base_de_datos.php";
    session_start();
    if(!isset($_SESSION["usuario"])){
        header("Location: ../Principal.php");
    }
    $usuario = $_SESSION["usuario"];
    $precioTotal = $_POST["precioTotal"];
    $idCesta = $_POST["idCesta"];
    $fechaActual = date('Y/m/d');
    $numeroProductos = $_POST["numeroProductos"];
    
    $sql = "INSERT INTO Pedidos (usuario, precioTotal, fechaPedido) VALUES ('$usuario', '$precioTotal', '$fechaActual')";
    $conexion -> query($sql);

    $sql = "SELECT idPedido FROM Pedidos WHERE usuario = '$usuario' AND precioTotal = '$precioTotal' AND fechaPedido = '$fechaActual'";
    $idPedido = $conexion -> query($sql) -> fetch_assoc()["idPedido"]; 

    $sql = "SELECT idProducto, cantidad FROM productosCestas WHERE idCesta = '$idCesta'";
    $aux = $conexion -> query($sql);
    
    $idProductos = [];
    $cantidades = [];
    while($fila = $aux -> fetch_assoc()){
        $idProductos[] = $fila["idProducto"];
        $cantidades[] = $fila["cantidad"];
    }

    for($i = 0; $i < $numeroProductos; $i++){
        $linea = $i + 1;
        $sql = "SELECT precio FROM Productos WHERE idProducto = '$idProductos[$i]'";
        $precio = $conexion -> query($sql) -> fetch_assoc()["precio"];
        $sql = "INSERT INTO lineasPedidos VALUES ('$linea', '$idProductos[$i]', '$idPedido', '$precio' , '$cantidades[$i]')";
        $conexion -> query($sql);
    }
    
    $cont = 0;
    while($cont < $numeroProductos){
        $sql = "DELETE FROM productosCestas WHERE idProducto = $idProductos[$cont]";
        $conexion -> query($sql);
        $cont++;
    }
    $sql = "UPDATE Cestas SET precioTotal = '0.0'  WHERE idCesta = '$idCesta'";
    $conexion -> query($sql);
    header('location: ../Principal.php'); 
?>