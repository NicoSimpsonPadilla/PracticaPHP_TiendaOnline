<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario_IniciarSesion_Usuario</title>
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/estilos.css">
    <?php require 'Utilidades/depurar.php'; ?>
    <?php require 'Utilidades/base_de_datos.php'; ?>
</head>
<body>
    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = depurar($_POST["usuario"]);
        $contrasena = depurar($_POST["contrasena"]);

        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $resultado = $conexion -> query($sql);

        if($resultado -> num_rows === 0) { 
        ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            NO EXISTE EL USUARIO
        </div>
        <?php
        } else {
            while ($fila = $resultado -> fetch_assoc()) {
                $contrasena_cifrada= $fila["contrasena"];
            }
    
            $acceso_valido = password_verify($contrasena, $contrasena_cifrada);
    
            if($acceso_valido) {
                ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    NOS HEMOS LOGEADO CON EXITO
                </div>
                <?php
                session_start();
                $_SESSION["usuario"] = $usuario;
                header('location: Principal.php');
            } else {
                ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    LA CONTRASEÑA ESTA MAL
                </div>
                <?php
            }
        }
    }
    ?>
    <div class="añadirProducto container">
        <div class="añadirProducto_content container">
            <h1>Formulario Inicio de sesion Usuario</h1>
            <div class="col-9">
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="form-label">Nombre de Usuario: </label>
                        <input class="form-control" type="text" name="usuario">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña: </label>
                        <input class="form-control" type="password" name="contrasena">
                    </div>
                    <button class="btn btn-dark" type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>