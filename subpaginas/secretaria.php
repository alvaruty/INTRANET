<?php 
session_start(); 
include("../base.php"); 
// info destacada
$sSQL = "SELECT `ruta` , `texto` , `seccion` , `departamento` FROM `enlaces` WHERE `seccion` = '100' AND `departamento` = '300'";
$result = mysqli_query($conexion, $sSQL);
$contador = 0;

while($row = mysqli_fetch_row($result)){
    $ruta[$contador] = $row[0];
    $texto[$contador] = $row[1];
    $contador++;
}

// enlaces
$sSQL = "SELECT `ruta` , `texto` , `seccion` , `departamento` FROM `enlaces` WHERE `seccion` = '101' AND `departamento` = '300' ORDER BY `fecha` DESC ";
$result = mysqli_query($conexion, $sSQL);
$contador1 = 0;

while($row = mysqli_fetch_row($result)){
    $rutaEnlace[$contador1] = $row[0];
    $textoEnlace[$contador1] = $row[1];
    $contador1++;
}

//var_dump($contador1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secretaria : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/secretaria.css">
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
    <section class="noticias">
        <!-- Contenido de secretaria -->
        <h2>Información destacada</h2>
        <div class="noticia">
            <img src="http://www.ieslassalinas.org/images/secretaria-y-administracion-slider_1-768x346.png" alt="info 1">
            <h3 class="h3-">Información General de Secretaría</h3>
            <p>Dirección: Camino de Seseña Nuevo s/n </p>
            <p>El horario de atención es de 9:00 a 14:00. </p>
            </br>
            <p> Puede contactar con nosotros a través del correo
                <a style="color: #2496ca; font-size:12.0pt" href="mailto:secretaria@ieslassalinas.org">secretaria@ieslassalinas.org</a> o del teléfono <a style="color:#2496ca; font-size:12.0pt" href="tel:+34918012657">91.801.26.57</a></p>
            <br><br><br><br>
            <h3>Información destacada</h3>
                <?php
                for($i = 0; $i < $contador; $i++){
                    echo "<br><a class='enlace-noticia' href=\"$ruta[$i]\" target=\"_blank\">$texto[$i]</a>";
                }
                ?>
        </div>
    </section>

    <section class="enlaces">
        <h2>Enlaces útiles</h2>
        <div class="enlaces-container">
            <ul>
            <?php
                for($i = 0; $i < $contador1; $i++){
                    echo "<a href=\"$rutaEnlace[$i]\">$textoEnlace[$i]</a><br>";
                }
                ?>
            </ul>
        </div>
    </section>
</section>
    
<?php include("../componentes/footer.php"); ?>

</body>
</html>
