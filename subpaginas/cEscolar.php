<?php 
session_start(); 
include("../base.php"); 
include("../componentes/header.php"); 

// documentacion
$sSQL = "SELECT `ruta` , `texto` FROM `enlaces` WHERE `seccion` = 1 AND `departamento` = 700 ORDER BY `fecha` DESC;";
$result = mysqli_query($conexion, $sSQL);
$contador = 0;

while($row = mysqli_fetch_row($result)){
    $ruta[$contador] = $row[0];
    $texto[$contador] = $row[1];
    $contador++;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.Escolar : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/cEscolar.css">
</head>
<body>
<body>
    <section class="contenido">
        
        <section class="archivos">
            <h5 align="center" style="color:2496ca">Administrar archivos:</h5><br>
            <a href="formulario_subida_fichero.php"><img title="Subir archivo" border="0" src="../../images/subir_archivo.jpg" width="61" height="62"></a>
            <a href="formulario_borrar_fichero_consejo_escolar.php"><img title="Borrar archivo" border="0" src="../../images/borrar_archivo.png" width="61" height="62"></a>
        </section>

        <section class="enlaces">
            <h2>Consejo escolar</h2>
            <div class="enlaces-container">
            <?php
                for($i = 0; $i < $contador; $i++){
                echo "<a href=\"$ruta[$i]\" target=\"_blank\">$texto[$i]</a><br>";
                }
                ?>
            </div>
        </section>
        
        <div class="imagen">
            <img src="../imagenes/consejoEscolarPNG.png" alt="">
        </div>
    </section>
</body>

<?php include("../componentes/footer.php"); ?>
