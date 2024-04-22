<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");

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
        <?php
            $txt_departamento=$_SESSION["txt_departamento"];
            $cd_departamento=$_SESSION["cd_departamento"];
            $usuario_act = $_SESSION["usuario"];
            //tomo el valor de un elemento de tipo texto del formulario 
            $cadenatexto = $_POST["cadenatexto"]; 
            $desplegable_secciones= $_POST["desplegable_secciones"];
            echo "<br>Escribió en el campo de texto: " . $cadenatexto . "<br><br>"; 


            
            //datos del arhivo 
            $nombre_archivo = $_FILES['userfile']['name']; 
            $tipo_archivo = $_FILES['userfile']['type']; 
            $tamano_archivo = $_FILES['userfile']['size']; 
        //	echo "nombre_archivo: ".$nombre_archivo."<br>";
        //	echo "tipo_archivo: ".$tipo_archivo."<br>";
        //	echo "tamaño_archivo: ".$tamano_archivo."<br>";
        //	echo "nombre_temporal_archivo: ".$_FILES['userfile']['tmp_name']."<br>";			
            
            // Eliminamos del nombre de archivo todo aquello que no sean letras, numeros o puntos.
            $nombre_archivo = revisa_nombre_archivo($nombre_archivo); 

            //compruebo si las características del archivo son las que deseo 
            if (!($tamano_archivo < $TAMANO_MAXIMO_DE_ARCHIVO)) { 
                echo "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .gif o .jpg<br><li>se permiten archivos de 100 Kb máximo.</td></tr></table>"; 
            }else{ 
        //		echo "llega hasta aqui1";
                $fecha =date("d-m-Y h-i-s" );
        //		echo "llega hasta aqui2";
                $ruta_destino = "uploads/".$cd_departamento."/".$fecha.$nombre_archivo;
        //		echo $ruta_destino;
                //$cadenatexto = $cadenatexto." ".$fecha; Ya no le metemos la fecha en el texto del archivo.
            
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $ruta_destino)){ 
                    echo "El archivo ha sido cargado correctamente."; 
                    //Además de subir el archivo, lo guardamos un registro en la base de datos

                    $hoy=date("Y-m-d");			
                    $cadenaSQL="insert into enlaces (ruta, texto, seccion, departamento,usuario, fecha) values ('$ruta_destino', '$cadenatexto', '$desplegable_secciones', $cd_departamento,'$usuario_act', '$hoy')";
        //			echo $cadenaSQL;
                    $result=mysqli_query($conexion, $cadenaSQL);
                }else{ 

                echo "<p>Ocurrió algún error al subir el fichero y no pudo guardarse.</p>"; 
                echo "<p> REVISE QUE EL NOMBRE DEL ARCHIVO NO CONTENGA CARACTERES EXTRAÑOS O TILDES.</p>";
                echo "<p>Nombre de archivo: $nombre_archivo</p>"; 
                echo "<p>Tamaño de archivo: $tamano_archivo</p>"; 
                echo "<p>Tipo de archivo: $tipo_archivo</p>"; 
                echo "<p>Ruta de destino: $ruta_destino</p>"; 
                } 
            } 
            mysqli_close($conexion);
        ?>
    </div>
<?php
include("../../componentes/footer.php");
?> 