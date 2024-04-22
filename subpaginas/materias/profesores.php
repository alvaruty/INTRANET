<?php 
session_start();
include ("utilities/seguridad.php");
include("../../base.php");
include("../../componentes/header.php"); 

$txt_departamento=$_SESSION["txt_departamento"];
$cd_departamento=$_SESSION["cd_departamento"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesores : IES Las Salinas</title>
    <link rel="icon" href="/../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/materias.css">
</head>
<body>

<div class="img">
        <a href="../materias.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
</div>
<section class="materia">
        <h2><?php echo "$txt_departamento" ?></h2>
        <h4>Zona restringida</h4>
        <div class="invisible-table">
            <div class="image-row">
                <div class="image-item">
                    <a href="formulario_subida.php"><img src="../../imagenes/enviar.png" alt="imagen 1" style="width: 100px;"></a>
                    <a href="formulario_subida.php" style="font-size: 14px;">Colgar Archivo</a>
                </div>
                <div class="image-item">
                    <a href="formulario_borrar_archivos.php"><img src="../../imagenes/borrar-archivo.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="formulario_borrar_archivos.php" style="font-size: 14px;">Borrar Archivo</a>
                </div>
                <div class="image-item">
                    <a href="Control_de_tareas_de_alumnos.php"><img src="../../imagenes/tareas-alumnos.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="Control_de_tareas_de_alumnos.php" style="font-size: 14px;">Tareas de alumnos</a>
                </div>
                <div class="image-item">
                    <a href="formulario_subida_archivos_multiple_a_alumnos.php"><img src="../../imagenes/enviar-archivos.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="formulario_subida_archivos_multiple_a_alumnos.php" style="font-size: 14px;">Enviar archivo a alumno</a>
                </div>
                <div class="image-item">
                    <a href="control_ficheros.php"><img src="../../imagenes/archivos-recibidos.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="materias/actuaciones.php" style="font-size: 14px;">Archivos recibidos</a>
                </div>
                <div class="image-item">
                    <a href="control_ficheros_a_alumnos.php"><img src="../../imagenes/archivos-enviados-alumnos.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="control_ficheros_a_alumnos.php" style="font-size: 14px;">Archivos enviados a alumnos</a>
                </div>
            </div>
        </div>

        <div class="columns-container">
            <div class="column-p">
                <h3>Listado completo de Archivos subidos al Departamento:</h3>
                <ul>
                <?php
                $contador=1;
                //Mostramos los archivos subidos a la PRINCIPAL
                $result=mysqli_query($conexion, "SELECT ruta, texto FROM enlaces where  departamento=$cd_departamento and seccion=100 order by seccion,texto");
                echo "<Br><b>Principal</b><Br>";
                while($row=mysqli_fetch_row($result))
                {
                    echo "<li>&nbsp<a href=\"$row[0]\" target=\"_blank\">$row[1]</a></li>";	
                }
                //Ahora mostramos todos los demás archivos.
                $la_seccion="o";
                $result=mysqli_query($conexion, "SELECT ruta, texto, txt_seccion FROM enlaces, configuracion_de_secciones where cd_seccion = seccion and departamento=$cd_departamento and cd_departamento =departamento and seccion>99 order by seccion,texto");
                while($row=mysqli_fetch_row($result)){
                    if ($row[2]	!=$la_seccion){
                        echo "<Br><b>$row[2]</b><Br>";
                        echo "<li><a href=\"$row[0]\" target=\"_blank\"> $row[1]</a></li>";	
                        //$curso = ($row[2] - 90)/10;//Chanchullo para transformar la sección en el curso del alumno
                        //echo "<li>&nbsp<a href=\"$row[0]\" target=\"_blank\">$row[1]----$curso º</a></li>";	
                        $la_seccion=$row[2];		
                    }else{
                        echo "<li><a href=\"$row[0]\" target=\"_blank\"> $row[1]</a></li>";	
                        //$curso = ($row[2] - 90)/10;//Chanchullo para transformar la sección en el curso del alumno
                		//echo "<li>&nbsp<a href=\"$row[0]\" target=\"_blank\">$row[1]----$curso º</a></li>";	
                    }	
                }
                mysqli_close($conexion);
                ?>
                </ul>
            </div>
            <div class="column-configurar">
                <a href="secciones/crea-seccion.php" style="display: flex; align-items: center;">
                    <img src="../../imagenes/configuraciones.PNG" alt="Configurar" width="35">
                    <h3 style="margin-left: 10px;">Configurar las secciones</h3>
                </a>
                <div>
                    <p>Configurar las secciones que aparecen dentro de Recursos, de modo que se adapten mejor a las necesidades del departamento.</p>
                </div>
            </div>

        </div>
    </section>

<?php include("../../componentes/footer.php"); ?>