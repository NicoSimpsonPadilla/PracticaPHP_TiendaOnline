<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina_Principal</title>
    <link rel="stylesheet" href="views/styles/bootstrap.min.css">
    <link rel="stylesheet" href="views/styles/estilos.css">
    <?php require "util/base_de_datos.php"; ?>
    <?php require "util/producto.php"; ?>
    <?php require "util/depurar.php"; ?>
</head>
<body>
    <?php
    // Usuario online
    session_start();
    if(isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
    } else {
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
    }

    // Añadir a cesta
    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $id_producto = depurar($_POST["id_producto"]);
        $precio = depurar($_POST["precio"]);
        $id_cesta = -1;
        $temp_cantidad = depurar($_POST["cantidad"]);

        // Defino la id_cesta
        $sql = "SELECT * FROM Cestas WHERE usuario = '$usuario'";
        $resultado = $conexion->query($sql);
        while ($fila = $resultado -> fetch_assoc()) {
            $id_cesta = $fila["idCesta"];
        }

        // Validacion cantidad
        if ($temp_cantidad < 1) {
            $err_cantidad = "La cantidad debe de ser mayor a 1";
        } else {
            if ($temp_cantidad > 5) {
                $err_cantidad = "La cantidad debe de ser menor a 5";
            } else {
                $cantidad = $temp_cantidad;
            }
        }

        // Comprobar que se pueda añadir productos
        $sql = "SELECT * FROM ProductosCestas WHERE idProducto = '$id_producto' && idCesta = '$id_cesta'";
        $resultado = $conexion->query($sql);

        while ($fila = $resultado -> fetch_assoc()) {
            $max_cantidad = $fila["cantidad"];
        }

        // Si esta lleno
        if ($max_cantidad + $cantidad > 10) {
            $err_cantidad = "No se puede añadir mas de este producto";

        // Si no esta lleno
        } else {

            // Envio a base de datos
            if(isset($id_producto) && $id_cesta != -1 && isset($cantidad)) {
                if ($max_cantidad == NULL) {
                    $sql = "INSERT INTO ProductosCestas (idProducto, idCesta, cantidad) 
                    VALUES ('$id_producto', '$id_cesta', '$cantidad')";
                    $conexion -> query($sql);
                } else {
                    $cantidad_total = $max_cantidad + $cantidad;
                    $sql = "UPDATE ProductosCestas 
                    SET cantidad = '$cantidad_total' 
                    WHERE (idProducto = '$id_producto' && idCesta = '$id_cesta')";
                    $conexion -> query($sql);
                }
            } 
            $precioCantidad = $cantidad * $precio;
            $sqlPrecioTotal = "UPDATE Cestas SET precioTotal = precioTotal + $precioCantidad WHERE idCesta = $id_cesta";
            $conexion-> query($sqlPrecioTotal);
        }
    }

    // Invocacion de los productos
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
        <?php
        if ($usuario == "invitado") {
            ?>
            <a href="Formulario_IniciarSesionUsuario.php" class="btn btn-outline-primary">Iniciar sesión</a>
            <a href="Formulario_RegistroUsuario.php" class="btn btn-outline-primary">Registrarse</a>
            <?php
        } else {
            ?>
            <a href="util/cerrar_sesion.php" class="btn btn-outline-primary">Cerrar sesión</a>
            <a href="Cesta.php" class="btn btn-outline-primary">Cesta</a>
            <?php
            if ($_SESSION["rol"] == "admin") {
                ?>
                <a href="Formulario_AñadirProducto.php" class="btn btn-outline-primary">Añadir producto</a>
                <?php
            }
        }
        echo "<br><br>";
        if(isset($err_cantidad)){
            ?><div class='alert alert-danger' role='alert'>
            <?php echo "$err_cantidad"; ?></div>
        <?php
        }
        ?>
    </div> 
    
    <div class="container">
        <h1>Inicio</h1>
        <table class="table table-secondary table-hover text-center">
            <thead class="table table-dark">
                <tr>
                    <th class="col-md-1">ID</th>
                    <th class="col-md-1">Nombre</th>
                    <th class="col-md-1">Precio</th>
                    <th class="col-md-2">Descripcion</th>
                    <th class="col-md-1">Cantidad</th>
                    <th class="col-md-6">Imagen</th>
                    <?php
                    if ($usuario != "invitado") {
                    ?>
                    <th>Cesta</th>
                        <?php
                        }
                        ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($productos as $producto) { ?>
                    <tr>
                        <td><?php echo $producto -> idProducto ?></td>
                        <td><?php echo $producto -> nombreProducto ?></td>
                        <td><?php echo $producto -> precio ?></td>
                        <td><?php echo $producto -> descripcion ?></td>
                        <td><?php echo $producto -> cantidad ?></td>
                        <td><?php echo "<img class='rounded' src=' " . $producto -> imagen . " ' width='50%' height='25%'/>" ?></td>
                        <?php
                        if ($usuario != "invitado") {
                        ?>
                        <td>
                            <form action="" method="post">
                                <select class="form-select" name="cantidad">
                                    <option selected value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <input type="hidden" name="id_producto"
                                value="<?php echo $producto -> idProducto ?>">
                                <input type="hidden" name="precio"
                                value="<?php echo $producto -> precio ?>">
                                <input class="btn btn-dark" type="submit" value="Añadir">
                            </form>
                        </td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>