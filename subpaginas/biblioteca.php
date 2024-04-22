<?php 
session_start(); 
include("../base.php"); 

// libros
$sSQL = "SELECT `ruta` , `texto` , `seccion` , `departamento` FROM `enlaces` WHERE `seccion` = '0' AND `departamento` = '22' ORDER BY `texto` ASC ";
$result = mysqli_query($conexion, $sSQL);
$contador = 0;

while($row = mysqli_fetch_row($result)){
    $ruta[$contador] = $row[0];
    $texto[$contador] = $row[1];
    $contador++;
}

//var_dump($contador1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/biblioteca.css">
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
    <div class="biblioteca">
        <h2>Biblioteca</h2>
        <div class="filas-enlaces">
            <div class="fila">
                <ul>
                <?php
                $mitad = ceil($contador / 2); // Obtener la mitad de los registros

                for($i = 0; $i < $mitad; $i++){
                    echo "<li><img src=\"../imagenes/libro-abierto.png\"> <a href=\"$ruta[$i]\">$texto[$i]</a></li>";
                }
                ?>
                </ul>
            </div>
            <div class="fila">
                <ul>
                <?php
                for($i = $mitad; $i < $contador; $i++){
                    echo "<li><img src=\"../imagenes/libro-abierto.png\"> <a href=\"$ruta[$i]\">$texto[$i]</a></li>";
                }
                ?>
                </ul>
            </div>
            <!-- Agrega más filas según sea necesario -->
        </div>
    </div>
</section>
    
<?php include("../componentes/footer.php"); ?>

</body>
</html>
