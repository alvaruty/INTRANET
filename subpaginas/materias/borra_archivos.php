<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");
	$txt_departamento=$_SESSION["txt_departamento"];
	$cd_departamento=$_SESSION["cd_departamento"];
	$numero_total_archivos=$_POST["numero_total_archivos"];

	include("../../componentes/header.php");
?> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir o borrar archivos de alumnos : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/formulario_subida.css">
</head>
<body>
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div style="min-height:50%;" class="container">
            <p>&nbsp;</p>	<font color="#2496ca">
        <?php
            echo "<p><b>Se procede a guardar las modificaciones realizadas. N�mero de registros: $numero_total_archivos</b></p>";

            $modificados=0;
            for ($i = 1; $i <= $numero_total_archivos; $i++) {
                $ruta=$_POST["ruta".$i];
                $cd_enlace=$_POST["cd_enlace".$i];
                $campo_modificado=$_POST["campo_modificado".$i];
                $campo_permisos=$_POST["valor_desplegable".$i];
                if ($campo_modificado==2){
                    //echo "<p>$ruta--$cd_enlace</p>";
                    $result=mysqli_query($conexion, "delete from enlaces where cd_enlace='$cd_enlace' and departamento=$cd_departamento");
                    if ($result ==1){
                        $modificados=$modificados+1;
                        unlink($ruta);
                        echo "El ejercicio se ha borrado correctamente.<br>";
                    }else{	
                        echo "Error al borrar el ejercicio.<br>";	
                    }		}
            }
            mysqli_close($conexion);
            echo "<p><b>Se han eliminado $modificados archivos en el servidor.</b></p>";
        ?>
    </div>
<?php
include("../../componentes/footer.php");
?> 