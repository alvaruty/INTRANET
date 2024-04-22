<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");
	//include ("../../../cadenas.php");		
	$txt_departamento=$_SESSION["txt_departamento"];
	$cd_departamento=$_SESSION["cd_departamento"];
	$cadenatexto = $_POST["cadenatexto"]; 
	$cd_agrupacion= $_POST["cd_agrupacion"];
	$contador_total=$_POST["total_contador"];	//esto es el total de la agrupacion
	$cd_actividad=$_POST["desplegable_actividades"];
?>
<html>
<head>
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../../../CSS/general.css">
</head>
<body>
<?php
	echo "Escribió en el campo de texto: " . $cadenatexto . "<br><br>"; 
	 
	//datos del arhivo 
	$nombre_temporal=$_FILES['userfile']['tmp_name'];
	$nombre_archivo = $_FILES['userfile']['name']; 
	$tipo_archivo = $_FILES['userfile']['type']; 
	$tamano_archivo = $_FILES['userfile']['size']; 
	// Eliminamos del nombre de archivo todo aquello que no sean letras, numeros o puntos.
	echo "Nombre inicial del archivo:";
	echo "  ".$nombre_archivo."<br>";
	$nombre_archivo = revisa_nombre_archivo($nombre_archivo);
	//compruebo si las características del archivo son las que deseo 
	echo "Nombre final del archivo:";
	echo "  ".$nombre_archivo."<br>";
	$guardado=0;
	$contador=1;//es el contador de los que enviamos, no del total de la agrupación
	if (!($tamano_archivo <  $TAMANO_MAXIMO_DE_ARCHIVO)) { 
	    echo "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .gif o .jpg<br><li>se permiten archivos de $TAMANO_MAXIMO_DE_ARCHIVO Mb máximo.</td></tr></table>"; 
	}else{ 
		$fecha =date("d-m-Y h-i-s" );
		//echo "el contador vale:".$contador_total;
		//Compiamos el fichero una vez menos que los alumnos, y la última vez que falta, en lugar de copiarlo, lo cortamos del temporal.
		$hoy=date("Y-m-d");
		for ($x=1 ;$x<$contador_total ;$x++){
			//echo "marcado?".$_POST["check".$x];
			$marcado = $_POST["check".$x];
			$usuario_act = $_POST["usuario".$x];
			
			if ($marcado==true){
				$ruta_destino = "uploads/".$cd_departamento."/".$fecha.$usuario_act.$nombre_archivo;
				$cadenatexto = $_POST["cadenatexto"]." ".$fecha;
				if (copy($_FILES['userfile']['tmp_name'], $ruta_destino)){ 
			        echo "El archivo ha sido cargado correctamente.<br>"; 
					//Además de subir el archivo, lo guardamos un registro en la base de datos
					//La SECCION 99 es la que uso para archivos subidos por el profesor, a la sección Mis documentos de los alumnos.
					$cadenaSQL="insert into enlaces_profesores (ruta, texto, cd_agrupacion, departamento,usuario, fecha) values ('$ruta_destino', '$cadenatexto', $cd_agrupacion, $cd_departamento,'$usuario_act', '$hoy')";
//					echo $cadenaSQL;
					$result=mysqli_query($conexion, $cadenaSQL);
					if ($result ==1){
						 echo "El archivo ha sido cargado correctamente."; 
						 $contador++;
					 	$guardado=1;
					}else{
						echo "ERROR EN EL PROCESO. NO SE HA PODIDO ENVIAR EL EJERCICIO.";
					 	$guardado=0;						
					}
					
//					echo $result;
			    }else{ 
			       echo "<p>Ocurrió algún error al subir el fichero y no pudo guardarse.</p>"; 
			       echo "<p>REVISE QUE EL NOMBRE DEL ARCHIVO NO CONTENGA CARACTERES EXTRAÑOS O TILDES.</p>";
				   echo "<p>Nombre de archivo: $nombre_archivo</p>"; 
			       echo "<p>Tamaño de archivo: $tamano_archivo</p>"; 
			       echo "<p>Tipo de archivo: $tipo_archivo</p>"; 
			       echo "<p>Ruta de destino: $ruta_destino</p>"; 
		    	} 
		    }//fin del if está marcado...
		}//fin del for
    	//borramos el archivo temporal que solo estabamos copiando, no cortanto, porque eran varias copias.
    	unlink($nombre_temporal);
	} 

	mysqli_close($conexion);
?>
<p></p>
Haga clic aquí para volver a la página anterior del

<a href="profesores.php">Departamento</a>

</body>
</html>
