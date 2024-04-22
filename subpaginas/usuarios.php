<?php 
session_start(); 
include("../base.php");
include("../componentes/header.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/usuarios.css">
</head>
<body>
<script type="text/javascript" language="JavaScript1.2">
        function pasa_codigo_asignatura(cod, text){
            formulario.cd_asignatura_seleccionada.value = cod;
            formulario.txt_asignatura_seleccionada.value = text;
        }
</script>

    <section class="formulario">
        <div class="formulario-container">
            <h2>Datos del usuario actual</h2>
            <form action="#" method="post">
                <div class="input-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre">
                </div>
                <div class="input-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos">
                </div>
                <div class="input-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="categoria">
                        <option value="">Seleccionar</option>
                        <option value="opcion1">Opción 1</option>
                        <option value="opcion2">Opción 2</option>
                        <option value="opcion3">Opción 3</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <button type="reset" class="btn-restablecer">Restablecer</button>
                </div>
            </form>
        </div>
    </section>
    
    <?php include("../componentes/footer.php"); ?>

</body>
</html>
