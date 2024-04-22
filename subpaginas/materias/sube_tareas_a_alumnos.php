<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");
	$txt_departamento=$_SESSION["txt_departamento"];
	$cd_departamento=$_SESSION["cd_departamento"];
	$cadenatexto = $_POST["cadenatexto"]; 
	$cd_agrupacion= $_POST["cd_agrupacion"];
	$contador_total=$_POST["total_contador"];	//esto es el total de la agrupacion
	$cd_actividad=$_POST["desplegable_actividades"];

	include("../../componentes/header.php");
?> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir archivos a alumnos : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/formulario_subida.css">
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
            //pongo 9 nueves para simular la longitud de un usuario y saber cuanto ocupa la ruta
            $ruta_destino = "uploads/".$cd_departamento."/999999999".$nombre_archivo;
            $cadenatexto = $_POST["cadenatexto"]." ".$fecha;
            $largo_nombre=strlen($cadenatexto);
            $largo_ruta=strlen($ruta_destino);
            if ($largo_nombre>=	$LONGITUD_MAXIMA_NOMBRE_ENLACE){
                echo "El texto introducido es demasiado largo<br>";			
                $contador_total = 0; //para no hacer nada, saltandonos el bucle
            }
            if($largo_ruta>=$LONGITUD_MAXIMA_RUTA_ENLACE){
                echo "El nombre del archivo es demasiado largo<br>";			
                $contador_total = 0; //para no hacer nada, saltandonos el bucle
            }
            
            for ($x=1 ;$x<$contador_total ;$x++){
                //echo "marcado?".$_POST["check".$x];
                $marcado = $_POST["check".$x];
                $usuario_act = $_POST["usuario".$x];
                $ruta_destino = "uploads/".$cd_departamento."/".$fecha.$usuario_act.$nombre_archivo;			
                if ($marcado==true){
                    if (copy($_FILES['userfile']['tmp_name'], $ruta_destino)){ 
                        echo "El archivo ha sido cargado correctamente.<br>"; 
                        //Además de subir el archivo, lo guardamos un registro en la base de datos
                        //La SECCION 99 es la que uso para archivos subidos por el profesor, a la sección Mis documentos de los alumnos.
                        $cadenaSQL="insert into enlaces_profesores (ruta, texto, cd_agrupacion, departamento,usuario, fecha) values ('$ruta_destino', '$cadenatexto', $cd_agrupacion, $cd_departamento,'$usuario_act', '$hoy')";
                        echo $cadenaSQL;
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
        //Si se ha asociado actividad al archivo subido, una vez creadas todas las copias, vamos a actualizar la tabla de actividades para ponerla como visible.

        if ($cd_actividad>0 and $guardado==1){



            $cadenaSQL="update actividades set visible=1 where cd_actividad=$cd_actividad";
            //echo $cadenaSQL;
            $result=mysqli_query($conexion, $cadenaSQL);
            if ($result ==1){
                echo "La actividad se ha hecho visible para los alumnos de la agrupacion.<br>";
            }
            else{	
                echo "Error al hacer visible la actividad.<br>";	
            }
            //Ahora hacemos que la actividad tenga como nota para todos un "pendiente de entrega". Si ya tuviese nota algún alumno
            // no se realizará bien en insert, porque hay clave única en la base de datos que impide la duplicidad de notas.
            //Primero recuperamos la evalación que corresponde con la actividad, para poder meter luego las notas
            $cadenaSQL="select evaluacion  from actividades  where cd_actividad=$cd_actividad";
            $result=mysqli_query($conexion, $cadenaSQL);
            while($row=mysqli_fetch_row($result)){
                $evaluacion=$row[0];
            }
            //Ahora hacemos los insert de las notas. Preparamos la cadena...
            for ($x=1 ;$x<$contador ;$x++){
                    $cadenaSQL="insert into notas (usuario, evaluacion, cd_actividad, nota, fecha_insercion) VALUES   ";
                    $usuario_act = $_POST["usuario".$x];
                    $marcado = $_POST["check".$x];
                    if ($marcado==true){
                        $cadenaSQL=$cadenaSQL." ('$usuario_act',$evaluacion , $cd_actividad, -1,'$hoy' )";
                        echo $cadenaSQL."<br>";
                        //... y hacemos la ins1erción
                        $result=mysqli_query($conexion, $cadenaSQL);
                        if ($result ==1){
                                echo "La nota se han puesto como Pendientes de entregar para el alumno de la agrupacion.<br>";
                        }else{	
                            echo "Error al insertar la nota la actividad.<br>";	
                        }
                    }
            }


            //Recuperamos todos los enlaces recien creados y comprobamos que sean los de este archivo
            $cadenaSQL="select cd_enlace,texto  from enlaces_profesores where cd_agrupacion=$cd_agrupacion and fecha='$hoy'";
    //		echo $cadenaSQL;
            $contador2=1;
            $result=mysqli_query($conexion, $cadenaSQL);
            while($row=mysqli_fetch_row($result)){
                //estos son los recien creados, ya que el texto es el mismo y además el texto lleva metida la fecha y hora.
                if($row[1]==$cadenatexto){
                    $cod_enlace[$contador2]=$row[0];
                    $contador2++;
                }
    //			echo "<br> texto: $row[1]<br>";
    //			echo "<br> cademadetexto: $cadenatexto<br>";
            }
            //El contador enviado por el formulario debería ser igual al contador de codigos recuperados
            echo "Contador: $contador  -- Contador2: $contador2 <br>";
            if($contador<>$contador2){
                echo "<h1>Se ha prodicido algún error al recuperar los códigos de los archivos enviados</h1>";
                echo "El número de códigos recuperados no coincide con el de archivos enviados.";
            }
            //Además añadimos la relación del archivo con la actividad en la tabla archivos_para_actividades	
            //ahora hacermos la inserción en la tabla archivos_para_actividades
            
        
            $j=1; //indice de la matriz de marcados, que puede ser uno o todos los enviados
            if ($contador2>0){		
                $cadenaSQL="insert into archivos_para_actividades (cd_archivo, cd_actividad, cd_agrupacion, usu_alumno) values  ";
                for ($x=1 ;$x<$contador_total ;$x++){
                    $usuario_act = $_POST["usuario".$x];
                    $marcado = $_POST["check".$x];
                    if ($marcado==true){
                        $cadenaSQL=$cadenaSQL." ($cod_enlace[$j],$cd_actividad, $cd_agrupacion,'$usuario_act'),";
                        $j++; //avanzamos en la matriz de marcados, una vez que se mete el enlace archivo-actividad
                    }
                }
                $largo=strlen($cadenaSQL) - 1;
                $cadenaSQL= substr($cadenaSQL,0, $largo );
                $cadenaSQL=$cadenaSQL.";";
                //echo $cadenaSQL;
                $result=mysqli_query($conexion, $cadenaSQL);
                if ($result ==1){
                    echo "Se han almacenado las actividades y los enlaces en la tabla archvios_para_actividades.<br>";
                }
                else{	
                    echo "Error al grabar la relación de los archivos con la actividad.<br>";	
                }
                
            }//fin del if de recuperar algún código de archivos		
        
        }	

    /*--------------------------------------------		
            Por motivos desconocidos, a veces se asignan mal los usuarios de algunas actividades, quedanto distinto usuario en una tabla y en la otra, de modo que no aparecen en el listado de actividades asignadas, aunque si les llegan a los alumnos.
        Esta sentencia corrigiendo el CD_ACTIVIDAD y el CD_AGRUPACION reasigna los usuairos destinatarios de los archivos y los pone igual a los que se les asigna la actividdad
        update enlaces_profesores,archivos_para_actividades set  enlaces_profesores.usuario= archivos_para_actividades.usu_alumno where archivos_para_actividades.cd_agrupacion= enlaces_profesores.cd_agrupacion and archivos_para_actividades.cd_archivo= enlaces_profesores.cd_enlace and archivos_para_actividades.cd_agrupacion= 3228 and archivos_para_actividades.cd_actividad= 36951 and respuesta_al_archivo is null
    ----------------------------------------*/	



        mysqli_close($conexion);
    ?><p></p>
    Haga clic aquí para volver a la página anterior del

    <a href="Control_de_tareas_de_alumnos.php">Departamento</a>

</body>