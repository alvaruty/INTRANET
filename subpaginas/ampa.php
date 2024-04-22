<?php 
include("../base.php"); 
session_start(); 

// Ampa
$sSQL = "SELECT `ruta`, `texto`, `seccion`, `departamento` FROM `enlaces` WHERE `seccion` = '1' AND `departamento` = '500'";
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
    <title>AMPA : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/ampa.css">
</head>
<body>
    <script type="text/javascript" language="JavaScript1.2">
        function pasa_codigo_asignatura(cod){
            //alert("Llega hasta aqui");
            form1.txt_codigo.value = cod;
        }
    </script>

<?php 
include("../componentes/header.php"); 
?>

    <section class="contenido">
        <section class="enlaces">
            <div class="enlaces-container">
                <h2>AMPA</h2>
                <?php
                for($i = 0; $i < $contador; $i++){
                echo "<a href=\"$ruta[$i]\" target=\"_blank\">$texto[$i]</a><br>";
                }
                ?>
            </div>
        </section>
        
        <div class="imagen">
            <img src="../imagenes/ampa.png" alt="">
        </div>
    </section>
    
    
    <?php include("../componentes/footer.php"); ?>

</body>
</html>
