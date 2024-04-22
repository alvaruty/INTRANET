<?php 
//session_start(); 
include("../../base.php");
include ("utilities/seguridad_alumnos.php");
include("../../componentes/header.php"); 

$txt_departamento=$_SESSION["txt_departamento"];
$cd_departamento=$_SESSION["cd_departamento"];
$usuario_act=$_SESSION["usuario"];
$cadenatexto = $_POST["cadenatexto"]; 
$cd_agrupacion = $_POST["desplegable_agrupaciones"]; 
$cd_archivo_profesor=$_POST["desplegable_tareas"];
$cadenatexto =corta_cadena_por_la_derecha($cadenatexto, 80);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .centrado p {
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
        <a href="../materias.php"><img src="../../../imagenes/hacia-atras.png" width="30" height="auto"></a>
    </div>
    <section class="centrado">
    <?php
	echo "Escribió en el campo de texto: " . $cadenatexto . "<br><br>"; 
	//datos del arhivo 
	$nombre_archivo = $_FILES['userfile']['name']; 
	$tipo_archivo = $_FILES['userfile']['type']; 
	$tamano_archivo = $_FILES['userfile']['size']; 
	$guardado=0;
	// Eliminamos del nombre de archivo todo aquello que no sean letras, numeros o puntos.
	$nombre_archivo = revisa_nombre_archivo($nombre_archivo); 
	//compruebo si las características del archivo son las que deseo 
	if (!($tamano_archivo < $TAMANO_MAXIMO_DE_ARCHIVO)) { 
	    echo "La extensión o el tamaño del archivo no es correcta. <br><br><table><tr><td><li>Se permiten archivos .gif o .jpg<br><li>se permiten archivos de 10 Mb máximo.</td></tr></table>"; 
	}else{ 
		$fecha =date("d-m-Y h-i-s" );
		$ruta_destino = "uploads/".$cd_departamento."/alu-".$fecha.$usuario_act.$nombre_archivo;
		$cadenatexto = $cadenatexto." ".$fecha;
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $ruta_destino)){ 

			//Además de subir el archivo, lo guardamos un registro en la base de datos

			$hoy=date("Y-m-d");
			$cadenaSQL="insert into enlaces_ejercicios (ruta, texto, cd_agrupacion, departamento,usuario,fecha) values ('$ruta_destino', '$cadenatexto', $cd_agrupacion, $cd_departamento,'$usuario_act','$hoy')";
			//echo $cadenaSQL;
			$result=mysqli_query($conexion, $cadenaSQL);
			if ($result ==1){
				 echo "El archivo ha sido cargado correctamente."; 
				 	$guardado=1;
			}else{
				echo "ERROR EN EL PROCESO. NO SE HA PODIDO ENVIAR EL EJERCICIO.";
			}			
	    }else{ 
				//echo "fecha: $fecha";
				//echo "usuario: $usuario_act";
				//echo "rutadestino: $ruta_destino";
				//echo "cadenatexto: $cadenatexto";
		       echo "<p>Ocurrió algún error al subir el fichero y no pudo guardarse.</p>"; 
		       echo "<p> REVISE QUE EL NOMBRE DEL ARCHIVO NO CONTENGA CARACTERES EXTRAÑOS O TILDES.</p>";
		       echo "<p>Nombre de archivo: $nombre_archivo</p>"; 
		       echo "<p>Tamaño de archivo: $tamano_archivo</p>"; 
		       echo "<p>Tipo de archivo: $tipo_archivo</p>"; 
	//	       echo "<p>Ruta de destino: $ruta_destino</p>"; 
			   //echo " la sql es: $cadenaSQL";
			   echo " ------------------------------NO PUDO GUARDARSE.----------------------------"; 
	    } 
	} 
	//si se ha guardado bien, hacemos un inser en la tabla de archivos_para_actividades

		
	mysqli_close($conexion);
?>
<p>Haga clic aquí para volver a la página principal del
<a href="../materias.php">Departamento</a></p>
    </section>
</body>

<?php 
include("../../componentes/footer.php"); 
?>