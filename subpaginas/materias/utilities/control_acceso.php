<?php 
include("../../../base.php");
include("../../../componentes/header.php"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso denegado : IES Las Salinas</title>
    <link rel="icon" href="/../imagenes/logo-sinFondo.png" type="image/x-icon">
    <style>
        .centrado {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh; /* Esto centrará los elementos verticalmente en toda la altura de la ventana */
        margin-top:-80px;
        }

        .centrado h2 {
            color:2496ca;
        }

        /* icono atras */
        .img {
            margin-left: 10%; 
            padding-top: 15px;
        }

    </style>
</head>
<body>
    <div class="img">
        <a href="../../materias.php"><img src="../../../imagenes/hacia-atras.png" width="30" height="auto"></a>
    </div>
    <section class="centrado">
        <h2>Debes registrarte como profesor para acceder</h2>
        <img src="../../../imagenes/acceso-denegado.png">
    </section>
</body>

<?php 
include("../../../componentes/footer.php"); 
?>