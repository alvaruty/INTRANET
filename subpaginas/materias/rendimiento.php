<?php 
//session_start(); 

include("../../base.php");
include ("utilities/seguridad_alumnos.php");
include("../../componentes/header.php");

if (empty($_SESSION["txt_departamento"])){
    $txt_departamento='';
}else{
    $txt_departamento=$_SESSION["txt_departamento"];
}
if (empty($_SESSION["cd_departamento_G"])){
    $cd_departamento=0;
}else{
    $cd_departamento=$_SESSION["cd_departamento_G"];
}
//var_dump($cd_departamento);

//Para mostrar los datos del hijo, comprobamos primero si es padre, y luego le quitamos la letra "p" que debe llevar al principio
$usuario_t=$_SESSION["usuario"];
$permisos=$_SESSION["permisos"];
if ($permisos==0){
	$usuario_alumno=quitaPdePadre($usuario_t);	

}else{
	$usuario_alumno=$usuario_t;	
}

$hoy = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendimiento : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/rendimiento.css">
</head>
<script type="text/javascript" language="JavaScript1.2">
function lanza_aviso_padres(){
    <?php
	//Consultamos la fecha de la última AP
	$sSQL="select max(fecha)";
	$sSQL=$sSQL."from actuaciones_particulares";
	$sSQL=$sSQL." where usu_alumno = '$usuario_alumno' ";
	//echo $sSQL;
	$result=mysqli_query( $conexion, $sSQL);
	while($row=mysqli_fetch_row($result)){	
		//Almaceno la fecha de la última AP para avisar a los padres si no la habían visto ya.
		$la_fecha_mas_reciente=$row[0];
	}
	//Consultamos el último acceso de los padres pero no en la tabla de usuarios porque ya se ha modificado
	//Por si no entró nunca, ponemos 2011-1-1 10:10:10 como ultimo acceso
    $fech ="2011-01-01";
    $hor="10:10:10";

	$f_acceso=$fech." ".$hor;
	//Debemos consultar en la de accesos y coger el segundo más reciente.
	$sSQL="select fecha, hora ";
	$sSQL=$sSQL."from  accesos ";
	$sSQL=$sSQL." where usuario = '".$usuario_t."' order by fecha desc, hora desc";
//	echo $sSQL;
	
	$result=mysqli_query( $conexion, $sSQL);
	$contador=0;
	while($row=mysqli_fetch_row($result)){
		if ($contador==1){ //solo cogemos la segunda fecha más reciente porque la más reciente es la de la sesión actual
			//Si la ultima actuacion es más reciente que el ultimo acceso, hacemos que consulte las AP desde el ultimo acceso
			$f_acceso=$row[0]." ".$row[1];
		}
		$contador++;
	}
	$total_evaluaciones=$contador;
	if ($f_acceso<$la_fecha_mas_reciente){
		echo "alert(\"Tiene nuevas Actuaciones Particulares.\");";			
		//echo "//fecha ultima actuacion=$la_fecha_mas_reciente";							
		//echo "//contador=$contador";											
		$la_fecha_mas_reciente=$f_acceso;
		echo "capa6.style.visibility='visible';";
		//marcamos de que se va a enviar para que se actualice la tabla de accesos y así no vuelva a salir el mensaje
		$enviado=1;
		//Ahora para que no siga saliendo, inserto un nuevo acceso en la tabla de accesos
   	    $fech =date("Y-m-d");
	    $hor=date("H:i:s");
	    
	    $sql="insert into accesos (usuario, fecha, hora) values ('$usuario_t', '$fech', '$hor')";
		$result=mysqli_query($conexion, $sql); 
		
	}	

?>
}
</script>
<body onload="lanza_aviso_padres()">
<div class="img">
        <a href="../materias.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
<section class="rendimiento-container">
    <table class="rendimiento-table wide">
        <caption>Calificaciones obtenidas:</caption>
        <tbody>
            <tr>
                <td>
                <?php
                //echo "llega hasta aqui";
                //este for retrasa mucho, hace 40 consultas inútiles para recuperar únicamente 5 evaluaciones.
                //hay que hacer una consulta a la tabla de evaluaciones y con los distintos códigos, hacer un bucle de 00 al número total, y repetari esta consulta con cada codigo de evaluacion
                    $sSQL="select cd_evaluacion from evaluaciones order by cd_evaluacion ";
                    $total_evaluaciones=0;
                    $result=mysqli_query( $conexion, $sSQL);
                    while($row=mysqli_fetch_row($result)){
                        $total_evaluaciones++;
                        $x[$total_evaluaciones]=$row[0];

                    }
                //echo "total evaluaciones:".$total_evaluaciones;

                for ($i=1;$i<=$total_evaluaciones;$i++){
                    $mostrar=1;  //mostar = 1 para que se vea el nombre de la evaluación la primera vez, luego lo pongo a cero.
                    $sSQL="select txt_actividad, nota, nombre, apellidos, fecha, txt_agrupacion, notas.evaluacion ";
                    $sSQL=$sSQL."from notas, actividades, agrupaciones_de_profesores, usuarios ";
                    $sSQL=$sSQL." where notas.usuario = '".$usuario_alumno."' and ";
                    $sSQL=$sSQL." agrupaciones_de_profesores.cd_agrupacion= actividades.cd_agrupacion and ";
                    $sSQL=$sSQL." agrupaciones_de_profesores.usuario_profesor= usuarios.usuario and ";		
                    $sSQL=$sSQL." notas.cd_actividad= actividades.cd_actividad and ";
                    $sSQL=$sSQL." actividades.visible=1 and ";
                    $sSQL=$sSQL." actividades.evaluacion=$x[$i] ";	
                    $sSQL=$sSQL." order by notas.evaluacion desc , actividades.cd_agrupacion, fecha desc ";
                    //echo $sSQL;
                    $materia=-1;
                    $result=mysqli_query( $conexion, $sSQL);
                    while($row=mysqli_fetch_row($result)){
                        if ($mostrar==1){ //Si es la primera vez, que se vea la evaluación , si no no.
                            if ($x[$i]<10){
                                echo "<br>";
                                echo "<h6>$x[$i] ª Evaluación.</h6>";
                                $materia=-1;//Para que se muestre la agrupacion al cambiar de evaluacion.
                            }else{
                                if ($x[$i]<40){
                                    $total=$x[$i]/10;
                                    $decimales = explode(".",$total);
                                    $xx = "Rec. $decimales[0]";
                                    echo "<br>";
                                    echo "<h6>$xx ª Evaluación.</h6>";
                                    echo "<br>";
                                }else{
                                    if ($x[$i]==40||$x[$i]==41){
                                        echo "<br>";
                                        echo "<h6>Evaluación final ordinaria.</h6>";
                                        echo "<br>";
                                    }					
                                    if ($x[$i]==50||$x[$i]==51){
                                        echo "<br>";
                                        echo "<h6>Evaluación final extraordinaria/Final.</h6>";
                                        echo "<br>";
                                    }					
                                }
                            }	
                            $mostrar=0; //Como ya se ha mostrado, pongo mostar a cero para que no se muestre hasta la siguiente 
                        }	
                        if ($materia!=$row[5]){
                            $materia=$row[5];
                            echo  "<font color=\"#800000\" size=\"2\"><b>&nbsp;&nbsp;+   $row[5]</b> ($row[2], $row[3])<br>";			
                        }else{
                            $materia=$row[5];		
                        }

                        if ($row[1]<5){
                            if ($row[1]<0){
                                //aqui debe ir el código para las notas menores que 0, es decir, las que no tiene que hacer, o las que no ha entregado
                                if ($row[1]==-1){ // -1 para las que debería haber entregado y aún no las ha entregado
                                    echo  "<font color=\"#000080\" size=\"2\">&nbsp;&nbsp;&nbsp;- $row[0] ($row[4]):";				
                                    echo "<font color=\"#FF0000\"> PENDIENTE DE ENTREGAR<br>";				
                                }	
                                if ($row[1]==-3){ // -3 para las que debería haber hecho y no la ha hecho, y tiene un cero
                                    echo  "<font color=\"#000080\" size=\"2\">&nbsp;&nbsp;&nbsp;- $row[0] ($row[4]):";				
                                    echo "<font color=\"#FF0000\"> SIN REALIZAR<br>";				
                                }				
                            }else{
                                echo  "<font color=\"#000080\" size=\"2\">&nbsp;&nbsp;&nbsp;- $row[0] ($row[4]):";
                                echo "<font color=\"#FF0000\"><b> $row[1]</b><br>";
                            }
                        }else{
                            echo  "<font color=\"#000080\" size=\"2\">&nbsp;&nbsp;&nbsp;- $row[0] ($row[4]):";
                            echo "<font color=\"#008000\"><b> $row[1]</b><br>";
                        }
                        echo  "<font color=\"#000080\" size=\"1\">";

                    }
                }

                ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="table-column">
        <table class="rendimiento-table">
            <caption>Tareas asignadas en materias del departamento</caption>
            <tbody>
                <tr>
                    <td>
                    <?php
                    /*
                        $sSQL="select cd_evaluacion from evaluaciones order by cd_evaluacion ";
                        $total_evaluaciones=0;
                        $result=mysqli_query( $conexion, $sSQL);
                        while($row=mysqli_fetch_row($result)){
                            $total_evaluaciones++;
                            $x[$total_evaluaciones]=$row[0];

                        }

                        for ($i=1;$i<=$total_evaluaciones;$i++){
                            $mostrar=1;  //mostar = 1 para que se vea el nombre de la evaluación la primera vez, luego lo pongo a cero.
                            $sSQL="select texto, txt_actividad, actividades.fecha,archivos_para_actividades.cd_archivo, enlaces_profesores.cd_agrupacion, departamentos.departamento, ruta, actividades.cd_actividad  ";
                            $sSQL=$sSQL." from enlaces_profesores, archivos_para_actividades, actividades, departamentos ";
                            $sSQL=$sSQL." where actividades.cd_actividad=archivos_para_actividades.cd_actividad and ";
                            $sSQL=$sSQL." enlaces_profesores.cd_enlace=archivos_para_actividades.cd_archivo and ";
                            $sSQL=$sSQL." departamentos.cd_departamento=enlaces_profesores.departamento and ";	
                            $sSQL=$sSQL." departamentos.cd_departamento=$cd_departamento and ";			
                            $sSQL=$sSQL." usu_alumno='$usuario_alumno' and ";
                            $sSQL=$sSQL." respuesta_al_archivo is null and "; // null es para archivos enviados por profes a alumnos
                            $sSQL=$sSQL." actividades.evaluacion=$x[$i] ";	
                            $sSQL=$sSQL." order by actividades.fecha desc ";
                            //echo $sSQL;
                            $actividad=-1;
                            $result=mysqli_query( $conexion, $sSQL);
                            $contador=0;
                            while($row=mysqli_fetch_row($result)){
                                $texto[$contador]=$row[0];
                                $txt_activ[$contador]=$row[1];
                                $fech_activ[$contador]=$row[2];
                                $cd_archiv[$contador]=$row[3];
                                $ruta_archiv[$contador]=$row[6];
                                $cd_activ[$contador]=$row[7];			
                                $contador++;	
                            }
                        
                            for ($j=0;$j<$contador;$j++){ //bucle que recorre los resultados de la select anterior

                                if ($mostrar==1){ //Si es la primera vez, que se vea la evaluación , si no no.
                                    if ($x[$i]<10){
                                        echo "<h6>$x[$i] ª Evaluación.</h6>";
                                        $materia=-1;//Para que se muestre la agrupacion al cambiar de evaluacion.
                                    }else{
                                        if ($x[$i]<40){
                                            $total=$x[$i]/10;
                                            $decimales = explode(".",$total);
                                            $xx = "Rec. $decimales[0]";
                                            echo "<h6>$xx ª Evaluación.</h6>";
                                        }else{
                                            if ($x[$i]==40||$x[$i]==41){
                                                echo "<h6>Evaluación final ordinaria.</h6>";
                                            }					
                                                if ($x[$i]==50||$x[$i]==51){
                                                echo "<h6>Evaluación final extraordinaria/Final.</h6>";
                                            }					
                                        }
                                    }	
                                    $mostrar=0; //Como ya se ha mostrado, pongo mostar a cero para que no se muestre hasta la siguiente 
                                }	
                                    
                                if ($actividad!=$txt_activ[$j]){
                                    echo "Actividad: $txt_activ[$j]<br>";
                                    //$actividad=$row[1];
                                    //cambio esto el 30-09-22 proque row[1] debe estar vacío fuera del while y no debe funcionar bien
                                    $actividad=$txt_activ[$j];
                                }
                                //Consultamos si la tarea está solo mandada o también respondida
                                //la única relación entre el archivo mandado y el respondido es la actviidad evaluable y el campo respuesta_al_archivo.
                                    
                                $sSQL="select texto, ruta, fecha  ";
                                $sSQL=$sSQL." from enlaces_ejercicios, archivos_para_actividades ";
                                $sSQL=$sSQL." where enlaces_ejercicios.cd_enlace=archivos_para_actividades.cd_archivo and ";
                                $sSQL=$sSQL." archivos_para_actividades.cd_actividad=$cd_activ[$j] and ";							
                                $sSQL=$sSQL." respuesta_al_archivo =$cd_archiv[$j] and "; // es un archivo que responde a otro enviado por un profe a alumnos
                                $sSQL=$sSQL." usu_alumno='$usuario_alumno' ";		

                                //echo $sSQL;
                                $actividad=-1;
                                $result=mysqli_query( $conexion, $sSQL);
                                $contador2=0;
                                while($row=mysqli_fetch_row($result)){
                                    $texto_respuesta[$contador2]=$row[0];
                                    $ruta_archiv_respuesta[$contador2]=$row[1];
                                    $fech_activ_respuesta[$contador2]=$row[2];
                                    $contador2++;	
                                }
                                
                                if($contador2>0){
                                echo "<img border=\"0\" src=\"../../../images/tarearespondida.png\" width=\"23\" height=\"23\">";
                                }else{
                                    echo "<img border=\"0\" src=\"../../../images/tarearecibida.png\" width=\"23\" height=\"23\">";
                                
                                }
                                echo "<a href=\"$ruta_archiv[$j]\"> $texto[$j]</a><br>";
                                if ($hoy>$fech_activ[$j]){
                                    echo "(La plazo de entrega acabó el: $fech_activ[$j])<br>";			
                                }else{
                                    echo "(Fecha límite: $fech_activ[$j])<a style=\"color: #FF0000\" href=\"formulario_subida_tareas.php\">Enviar la tarea</a><br>";						
                                }
                                
                                for ($xx=0;$xx<$contador2;$xx++){
                                        echo " - ENVIADA: <a href=\"$ruta_archiv_respuesta[$xx]\"> $texto_respuesta[$xx]($fech_activ_respuesta[$xx])</a><br>";
                                }
                                echo "<br>";
                            }
                        }
                    */
                    ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="rendimiento-table">
            <caption>Tareas asignadas en materias de OTROS DEPARTAMENTOS</caption>
            <tbody>
                <tr>
                    <td>
                    <?php

                    $sSQL="select cd_evaluacion from evaluaciones order by cd_evaluacion ";
                    $total_evaluaciones=0;
                    $result=mysqli_query( $conexion, $sSQL);
                    while($row=mysqli_fetch_row($result)){
                        $total_evaluaciones++;
                        $x[$total_evaluaciones]=$row[0];

                    }

                    for ($i=1;$i<=$total_evaluaciones;$i++){
                        $mostrar=1;  //mostar = 1 para que se vea el nombre de la evaluación la primera vez, luego lo pongo a cero.
                        $sSQL="select texto, txt_actividad, actividades.fecha,archivos_para_actividades.cd_archivo, enlaces_profesores.cd_agrupacion, departamentos.departamento, ruta, actividades.cd_actividad  ";
                        $sSQL=$sSQL." from enlaces_profesores, archivos_para_actividades, actividades, departamentos ";
                        $sSQL=$sSQL." where actividades.cd_actividad=archivos_para_actividades.cd_actividad and ";
                        $sSQL=$sSQL." enlaces_profesores.cd_enlace=archivos_para_actividades.cd_archivo and ";
                        $sSQL=$sSQL." departamentos.cd_departamento=enlaces_profesores.departamento and ";		
                        $sSQL=$sSQL." departamentos.cd_departamento<>$cd_departamento and ";			
                        $sSQL=$sSQL." usu_alumno='$usuario_alumno' and ";
                        $sSQL=$sSQL." respuesta_al_archivo is null and "; // null es para archivos enviados por profes a alumnos
                        $sSQL=$sSQL." actividades.evaluacion=$x[$i] ";	
                        $sSQL=$sSQL." order  by departamento, actividades.fecha desc ";
                        //echo $sSQL;
                        $actividad=-1;
                        $result=mysqli_query( $conexion, $sSQL);
                        $contador=0;
                        while($row=mysqli_fetch_row($result)){
                            $texto[$contador]=$row[0];
                            $txt_activ[$contador]=$row[1];
                            $fech_activ[$contador]=$row[2];
                            $cd_archiv[$contador]=$row[3];
                            $depart[$contador]=$row[5];
                            $ruta_archiv[$contador]=$row[6];
                            $cd_activ[$contador]=$row[7];			
                            $contador++;	
                        }
                        $dep="";
                        for ($j=0;$j<$contador;$j++){ //bucle que recorre los resultados de la select anterior
                            
                            if ($mostrar==1){ //Si es la primera vez, que se vea la evaluación , si no no.
                                if ($x[$i]<10){
                                    echo "<br>";
                                    echo "<h6>$x[$i] ª Evaluación.</h6>";
                                    echo "<br>";
                                    $materia=-1;//Para que se muestre la agrupacion al cambiar de evaluacion.
                                }else{
                                        $total=$x[$i]/10;
                                        $decimales = explode(".",$total);
                                        $xx = "Rec. $decimales[0]";
                                        echo "<br>";
                                        echo "<h6>$xx ª Evaluación.</h6>";
                                        echo "<br>";
                                }	
                                $mostrar=0; //Como ya se ha mostrado, pongo mostar a cero para que no se muestre hasta la siguiente 
                            }	
                            //Ahora mostramos el departamento...
                            if ($dep!=$depart[$j]){
                                $dep=$depart[$j];
                                echo "<p> Departamento de $dep</p>";
                            }
                                
                            if ($actividad!=$txt_activ[$j]){
                                echo "Actividad: $txt_activ[$j]<br>";
                                //$actividad=$row[1];
                                //cambio esto el 30-09-22 proque row[1] debe estar vacío fuera del while y no debe funcionar bien
                                $actividad=$txt_activ[$j];

                            }
                            //Consultamos si la tarea está solo mandada o también respondida
                            //la única relación entre el archivo mandado y el respondido es la actviidad evaluable y el campo respuesta_al_archivo.
                                
                            $sSQL="select texto, ruta, fecha  ";
                            $sSQL=$sSQL." from enlaces_ejercicios, archivos_para_actividades ";
                            $sSQL=$sSQL." where enlaces_ejercicios.cd_enlace=archivos_para_actividades.cd_archivo and ";
                            $sSQL=$sSQL." archivos_para_actividades.cd_actividad=$cd_activ[$j] and ";							
                            $sSQL=$sSQL." respuesta_al_archivo =$cd_archiv[$j] and "; // es un archivo que responde a otro enviado por un profe a alumnos
                            $sSQL=$sSQL." usu_alumno='$usuario_alumno' ";		
                                    
                            //echo $sSQL;
                            $actividad=-1;
                            $result=mysqli_query( $conexion, $sSQL);
                            $contador2=0;
                            while($row=mysqli_fetch_row($result)){
                                $texto_respuesta[$contador2]=$row[0];
                                $ruta_archiv_respuesta[$contador2]=$row[1];
                                $fech_activ_respuesta[$contador2]=$row[2];
                                $contador2++;	
                            }

                            if($contador2>0){
                            echo "<br><img border=\"0\" src=\"../../../images/tarearespondida.png\" width=\"45\" height=\"45\">";
                            }else{
                                echo "<br><img border=\"0\" src=\"../../../images/tarearecibida.png\" width=\"45\" height=\"45\">";
                            }
                            echo "<a href=\"$ruta_archiv[$j]\"> $texto[$j]</a><br>";
                            echo "(Fecha límite: $fech_activ[$j])<a style=\"color: #FF0000\" href=\"formulario_subida_tareas.php\">Enviar tarea</a>";			
                            for ($xx=0;$xx<$contador2;$xx++){
                                    echo " - ENVIADA: <a href=\"$ruta_archiv_respuesta[$xx]\"> $texto_respuesta[$xx]($fech_activ_respuesta[$xx])</a><br>";
                            }
                        }
                    }
                    ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="border-width:1px; padding:5px; border-style:outset; position: absolute; width: 567px; height: 496px; z-index: 6; left: 160px; top: 200px; visibility:hidden " id="capa6">
<table width="100%" Class=ayuda>
<tr>
<td>
<h3>Se han realizado las siguientes actuaciones particulares sobre su hijo/a desde su último acceso:</h3>


<h4>
<ul>
<?php
	$sSQL="select actuacion , usu_alumno , fecha, nombre, apellidos,comentario, comentario_visible ";
	$sSQL=$sSQL."from actuaciones_particulares, actuaciones, usuarios ";
	$sSQL=$sSQL." where usu_alumno = '$usuario_alumno' and ";
	$sSQL=$sSQL." usuarios.usuario = actuaciones_particulares.usu_profesor and ";
	$sSQL=$sSQL." actuaciones.cd_actuacion=actuaciones_particulares.tipo ";
	$sSQL=$sSQL." and fecha >'$la_fecha_mas_reciente' ";	
	$sSQL=$sSQL." order by  fecha desc";
	//echo $sSQL;
	$result=mysqli_query( $conexion, $sSQL);
	while($row=mysqli_fetch_row($result)){
		echo  "<li>";
		
		echo "<b> $row[0]: </b><font color=\"#000080\">&nbsp;&nbsp;&nbsp;&nbsp;(Fecha(año-mes-dia):$row[2]) (Profesor: $row[3] $row[4]) ";
		
		if ($row[6]==1){
			echo " $row[5] <Br>";
		}				
		echo "</font></li>";
	}
	
	

		mysqli_close($conexion); 
?>

	&nbsp;<img onclick="capa6.style.visibility='hidden';" border="0" id="img9" src="Imagenes/cierraactuacionesnuevas3.jpg" height="20" width="100" alt="Cerrar" onmouseover="FP_swapImg(1,0,/*id*/'img9',/*url*/'Imagenes/cierraactuacionesnuevas1.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img9',/*url*/'Imagenes/cierraactuacionesnuevas3.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img9',/*url*/'Imagenes/cierraactuacionesnuevas2.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img9',/*url*/'Imagenes/cierraactuacionesnuevas1.jpg')" fp-style="fp-btn: Brick Row 4" fp-title="Cerrar">

</ul>
</h4>

</td>
</tr>

</table>
	</div>
</section>


</body>
<?php include("../../componentes/footer.php"); ?>