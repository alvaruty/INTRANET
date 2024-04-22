<?php 
session_start(); 
include("../base.php"); 

// noticias
$sSQL = "SELECT `ruta` , `texto` , `seccion` , `departamento` FROM `enlaces` WHERE `seccion` = '1' AND `departamento` = '400' ORDER BY `fecha` DESC";
$result = mysqli_query($conexion, $sSQL);
$contador1 = 0;

while($row = mysqli_fetch_row($result)){
    $ruta[$contador1] = $row[0];
    $texto[$contador1] = $row[1];
    $contador1++;
}

// enlaces
$sSQL = "SELECT `ruta` , `texto` , `seccion` , `departamento` FROM `enlaces` WHERE `seccion` = '3' AND `departamento` = '400' ORDER BY `fecha` DESC ";
$result = mysqli_query($conexion, $sSQL);
$contador = 0;

while($row = mysqli_fetch_row($result)){
    $rutaEnlace[$contador] = $row[0];
    $textoEnlace[$contador] = $row[1];
    $contador++;
}

//var_dump($contador1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novedades : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/novedades.css">
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
        <!-- Contenido de las noticias -->
        <h2>Novedades</h2>
        <div class="noticia">
            <img src="../imagenes/novedades2.png" alt="Noticia 1">
            <h3 class ="a">Actualidad del centro</h3><br>
            <?php
            for($i = 0; $i < $contador1; $i++){
                echo "<span>•</span> <a target=\"blank\" href=\"$ruta[$i]\">$texto[$i]</a><br>";
            }
            ?>
        </div>
        <div class="noticia">
            <h3>Prometeo</h3>
            <p>Video tutorial de prometeo</p>
            <a href="#">Leer más</a>
        </div>
    </section>

    <section class="enlaces">
        <div class="enlaces-container">
            <h2>Enlaces</h2>
            <?php
            for($i = 0; $i < $contador; $i++){
                echo "<a target=\"blank\" href=\"$rutaEnlace[$i]\">$textoEnlace[$i]</a><br>";
            }
            ?>
        </div>
    </section>
</section>
    
<?php include("../componentes/footer.php"); ?>

</body>
</html>
