<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");
	$txt_departamento=$_SESSION["txt_departamento"];
	$cd_departamento=$_SESSION["cd_departamento"];
	$numero_total_archivos_enviados=$_POST["total_enviados"];

    include ("../../componentes/header.php");
?> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar tareas : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/formulario_subida.css">
</head>
<body>
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div class="container" style="min-height:48.1%;">
            <p>

        <?php
            $modificados=0;
            echo "total enviados $numero_total_archivos_enviados";
            for ($i = 0; $i < $numero_total_archivos_enviados; $i++) {
                $ruta=$_POST["ruta".$i];
                $cd_enlace=$_POST["cd_enlace".$i];
                $campo_modificado=$_POST["campo_modificado".$i];
                if ($campo_modificado==2){
                    echo "<p>$ruta--$cd_enlace</p>";
        //			echo "delete from enlaces_ejercicios where cd_enlace='$cd_enlace'";
        //			echo "delete from archivos_para_actividades where cd_archivo='$cd_enlace'";
                    $result=mysqli_query($conexion, "delete from enlaces_ejercicios where cd_enlace='$cd_enlace'");
                    $result=mysqli_query($conexion, "delete from archivos_para_actividades where cd_archivo='$cd_enlace'");
                    //Y AHORA BORRAMOS TAMBIÉN TODOS LOS ARCHIVOS ENVIADOS COMO RESPUESTA A ESA TAREA...
        //			echo "delete from archivos_para_actividades where respuesta_al_archivo='$cd_enlace'";
                    $result=mysqli_query($conexion, "delete from archivos_para_actividades where respuesta_al_archivo='$cd_enlace'");			
                    $modificados=$modificados+1;
                    unlink($ruta);
                }
                //Si no estamos borrando un achivo enviado a alumno, vamos a ver si estamos borrando alguna de las respuestas suyas
                if (empty($_POST["total_recividos_de_enviados$i"])){
                    $numero_total_archivos_recividos=0;		
                }else{
                    $numero_total_archivos_recividos=$_POST["total_recividos_de_enviados$i"];		
                }

                for ($j = 0; $j < $numero_total_archivos_recividos; $j++) {
                    $ruta=$_POST["ruta$i_$j"];
                    $cd_enlace=$_POST["cd_enlace".$i."_".$j];
                    $campo_modificado=$_POST["campo_modificado".$i."_".$j];
                    if ($campo_modificado==2){
        //				echo "delete from enlaces_ejercicios where cd_enlace='$cd_enlace'";
        //				echo "delete from archivos_para_actividades where cd_archivo='$cd_enlace'";
                        $result=mysqli_query($conexion, "delete from enlaces_ejercicios where cd_enlace='$cd_enlace'");
                        $result=mysqli_query($conexion, "delete from archivos_para_actividades where cd_archivo='$cd_enlace'");
                        //Y AHORA BORRAMOS TAMBIÉN TODOS LOS ARCHIVOS ENVIADOS COMO RESPUESTA A ESA TAREA...
        //				echo "delete from archivos_para_actividades where respuesta_al_archivo='$cd_enlace'";
                        $result=mysqli_query($conexion, "delete from archivos_para_actividades where respuesta_al_archivo='$cd_enlace'");			
                        $modificados=$modificados+1;
                        unlink($ruta);
                    }
                }

            }
            mysqli_close($conexion);
            echo "<p><b>Se han eliminado $modificados archivos en el servidor.</b></p>";
        ?>
        <i><u></u></i>
        </p>
    </div>
<?php
include("../../componentes/footer.php");
?> 
