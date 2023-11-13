<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina_Principal</title>
    <link rel="stylesheet" href="CSS/estilos.css">
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <?php require "Utilidades/base_de_datos.php"; ?>
    <?php require "Utilidades/producto.php"; ?>
</head>
<body>
    <?php
    //Usuario online
    session_start();
    if(isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
    } else {
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
    }

    //Invocacion de los productos
    $sql = "SELECT * FROM productos";
    $resultado = $conexion->query($sql);
    $productos = [];

    while ($fila = $resultado -> fetch_assoc()) {
        $nuevo_producto = new Producto(
            $fila["idProducto"], 
            $fila["nombreProducto"], 
            $fila["precio"],
            $fila["descripcion"], 
            $fila["cantidad"], 
            $fila["imagen"]
        );
        array_push($productos, $nuevo_producto);
    }
    ?>
    <div class="alert alert-primary align-items-center" role="alert">
        <h1>Pagina principal</h1>
        <h2>Bienvenid@ <?php echo $usuario ?></h2>
        <a href="Utilidades/cerrar_sesion.php" class="btn btn-outline-primary">Cerrar sesión</a>
    </div>
    
    <div class="container">
        <h1>Inicio</h1>
        <table class="table table-secondary table-hover">
            <thead class="table table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($productos as $producto) {
                    echo "<tr>";
                    echo "<td>" . $producto -> idProducto . "</td>";
                    echo "<td>" . $producto -> nombreProducto . "</td>";
                    echo "<td>" . $producto -> precio . "</td>";
                    echo "<td>" . $producto -> descripcion . "</td>";
                    echo "<td>" . $producto -> cantidad . "</td>";
                    echo "<td> <img src=' ". $producto -> imagen ." ' width='100%' height='50%'/></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>