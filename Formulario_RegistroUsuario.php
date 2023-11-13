<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario_Registro_Usuario</title>
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/estilos.css">
    <?php require 'Utilidades/depurar.php'; ?>
    <?php require 'Utilidades/base_de_datos.php'; ?>
</head>
<body>
<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $temp_usuario = depurar($_POST["usuario"]);
        $temp_contrasena = depurar($_POST["contrasena"]);
        $temp_fechaNacimiento = depurar($_POST["fechaNacimiento"]);

        // Validacion del nombre de usuario
        if(strlen($temp_usuario) < 4) {
            $err_usuario = "El usuario debe de tener como minimo 4 caracteres.";
        } else {
            if (strlen($temp_usuario) > 12) {
                $err_usuario = "El usuario debe de tener como maximo 12 caracteres";
            } else {
                // /^[a-zA-Z_]$/
                $patron = "/^[a-zA-Z_]+$/";
                if (!preg_match($patron, $temp_usuario)) {
                    $err_usuario = "El nombre de usuario solo puede tener letras y barrabajas";
                } else {
                    $usuario = $temp_usuario;
                }
            }
        }

        // Validacion de la contraseña
        if(!strlen($temp_contrasena) > 0) {
            $err_contrasena = "Debe de haber contraseña.";
        } else {
            if (strlen($temp_contrasena) > 255) {
                $err_contrasena = "La cantidad no puede ser tan larga";
            } else {
                $contrasena = $temp_contrasena;
            }
        }

        // Validacion de la fecha de nacimiento
        if (strlen($temp_fechaNacimiento) == 0) {
            $err_fechaNacimiento = "La fecha de nacimiento es obligatoria";
        } else {
            $fecha_actual = date("Y-m-d");
            list($anyo_actual, $mes_actual, $dia_actual) = explode("-", $fecha_actual);
            list($anyo, $mes, $dia) = explode("-", $temp_fechaNacimiento);
            if (($anyo_actual-$anyo > 12) && ($anyo_actual - $anyo < 120)) {
                $fechaNacimiento = $temp_fechaNacimiento;
            } else if (($anyo_actual - $anyo) < 12 || ($anyo_actual - $anyo) > 120) {
                $err_fechaNacimiento = "No puede ser menor de 12 años ni mayor de 120";
            } else {
                if ($mes_actual - $mes > 0) {
                    $fechaNacimiento = $temp_fechaNacimiento;
                } else if ($mes_actual - $mes < 0) {
                    $err_fechaNacimiento = "No puedes ser menor de 12 años ni mayor de 120";
                } else {
                    if ($dia_actual - $dia >= 0) {
                        $fechaNacimiento = $temp_fechaNacimiento;
                    } else {
                        $err_fechaNacimiento = "No puedes ser menor de 12 años ni mayor de 120";
                    }
                }
            }
        }
    }
    ?>

    <div class="añadirProducto container">
        <div class="añadirProducto_content container">
            <h1>Formulario Registro Usuario</h1>
            <div class="col-9">
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="form-label">Nombre de Usuario: </label>
                        <input class="form-control" type="text" name="usuario">
                        <?php if(isset($err_usuario)) echo $err_usuario ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña: </label>
                        <input class="form-control" type="password" name="contrasena">
                        <?php if(isset($err_contrasena)) echo $err_contrasena ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de nacimiento: </label>
                        <input class="form-control" type="date" name="fechaNacimiento">
                        <?php if(isset($err_fechaNacimiento)) echo $err_fechaNacimiento ?>
                    </div>
                    <button class="btn btn-dark" type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    if(isset($usuario) && isset($contrasena) && isset($fechaNacimiento)) {

        $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Usuarios (usuario, contrasena, fechaNacimiento) 
        VALUES ('$usuario', '$contrasena_cifrada', '$fechaNacimiento')";
        $conexion -> query($sql);
        
        $sql = "INSERT INTO Cestas (usuario, precioTotal) 
        VALUES ('$usuario', '0')";
        $conexion -> query($sql);
    }
    ?>
</body>
</html>