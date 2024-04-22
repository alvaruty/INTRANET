<?php 
session_start(); 
include("../base.php"); 
// Ampa
$sSQL = "SELECT `ruta`, `texto`, `seccion`, `departamento` FROM `enlaces` WHERE `seccion` = '100' AND `departamento` = '200'";
$result = mysqli_query($conexion, $sSQL);
$contador = 0;

while($row = mysqli_fetch_row($result)){
    $ruta[$contador] = $row[0];
    $texto[$contador] = $row[1];
    $contador++;
}

//var_dump($contador);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jefatura : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/jefatura.css">
</head>
<body>

<?php 
include("../componentes/header.php"); 
?>

    <section class="contenido">
        <section class="enlaces">
            <h2>Jefatura</h2>
            <div class="enlaces-container">
            <?php
                for($i = 0; $i < $contador; $i++){
                echo "<a href=\"$ruta[$i]\" target=\"_blank\">$texto[$i]</a><br>";
                }
                ?>
            </div>
        </section>
        
        <div class="imagen">
            <img src="../imagenes/mesa_redonda.png" alt="">
        </div>
    </section>
    
    
    <?php include("../componentes/footer.php"); ?>

</body>
</html>
