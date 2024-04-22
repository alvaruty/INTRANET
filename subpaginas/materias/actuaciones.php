<?php 
include("../../base.php");
include ("utilities/seguridad_alumnos.php");
include("../../componentes/header.php"); 

$txt_departamento=$_SESSION["txt_departamento"];
$cd_departamento=$_SESSION["cd_departamento"];
//Para mostrar los datos del hijo, comprobamos primero si es padre, y luego le quitamos la letra "p" que debe llevar al principio
$usuario_t=$_SESSION["usuario"];
$permisos=$_SESSION["permisos"];
if ($permisos==0){
    $usuario_alumno=quitaPdePadre($usuario_t);	

}else{
    $usuario_alumno=$usuario_t;	
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actuaciones particulares : IES Las Salinas</title>
    <link rel="icon" href="/../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/actuaciones.css">
    <script type="text/javascript">
        function lanza_aviso_padres(){
            <?php
                //Consultamos la fecha de la última AP
                $sSQL="select max(fecha)";
                $sSQL=$sSQL."from actuaciones_particulares";
                $sSQL=$sSQL." where usu_alumno = '$usuario_alumno' ";
            //	echo $sSQL;
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
            //	    echo $sql;
                    $result=mysqli_query($conexion, $sql ); 
            //		echo "llega hasta aquí";
                }	
            ?>
    </script>

</head>
<body onload="lanza_aviso_padres()">

<div class="img">
        <a href="../materias.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
</div>
<div class="container">
    <div class="titulo">
        <h2 style="color: #2496ca;">Actuaciones particulares</h2>
    </div>

    <div class="table-container">
        <table class="file-table">
            <thead>
                <tr>
                    <th>Actuaciones particulares (<?php 

                    //echo "usuario: $usuario_t ---usuario_alumno= $usuario_alumno";
                    //Para mostrar los datos del hijo, comprobamos primero si es padre, y luego le quitamos la letra "p" que debe llevar al principio
                    if ($permisos==0){
                        $nombre_alu ='';
                        $apellido_alu='';
                        $result=mysqli_query($conexion, "SELECT nombre, apellidos FROM usuarios where usuario='$usuario_alumno'");
                        while($row=mysqli_fetch_row($result)){
                            $nombre_alu=$row[0];
                            $apellido_alu=$row[1];			
                        }	
                    }else{
                            $nombre_alu=$_SESSION["nombre_u"];
                            $apellido_alu=$_SESSION["apellido_u"];			
                    }
                    echo " $nombre_alu $apellido_alu";	
                    ?>

                    )</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <ul>
                            <?php
                            //AQUÍ NO SALEN LAS ACTUACIONES DE TIPO 2, ES DECIR, EL CONSEJO Y LOS ITEMS PARA EL CURSO QUE VIENE
                            $sSQL="select actuacion , usu_alumno , fecha, nombre, apellidos,comentario, comentario_visible ";
                            $sSQL=$sSQL."from actuaciones_particulares, actuaciones, usuarios ";
                            $sSQL=$sSQL." where usu_alumno = '$usuario_alumno' and ";
                            $sSQL=$sSQL." usuarios.usuario = actuaciones_particulares.usu_profesor and ";
                            $sSQL=$sSQL." actuaciones.cd_actuacion=actuaciones_particulares.tipo  and actuaciones.tipo < 2 order by fecha desc";
                            //echo $sSQL;
                            $result=mysqli_query( $conexion, $sSQL);
                            while($row=mysqli_fetch_row($result)){
                                echo "<li>";	
                                echo "<strong>$row[0]: (año-mes-dia: $row[2]) <span>(Profesor: $row[3] $row[4]) </strong></span>";
                                if ($row[6]==1){
                                    echo  "<span>$row[5].</span>";
                                }				
                                echo "</li>";
                            }                            
                            
                            ?>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php

            /*en el 2020 se decide que no se necesita mostrar el consejo orientador, por lo que 
            todo esto se convierte en comentario
                //Recuperamos solo el último consejo orientador
                $sSQL="select fecha from actuaciones_particulares where tipo =40 and usu_alumno = '$usuario_alumno' ";
                $sSQL=$sSQL." ORDER BY fecha DESC";		
                //echo $sSQL;
                $contador=0;
                $result=mysqli_query( $conexion, $sSQL);
                while($row=mysqli_fetch_row($result)){
                    $fechas[$contador]=$row[0];
                    $contador++;
                }
                $total_consejos=$contador;
                //SIN NO SE HA CREADO EL CONSEJO ORIENTADOR, NO SALDRÁN LOS ITEMS DE 
                //NIVEL ALCANZADO Y REFUERZO PARA EL CURSO QUE VIENE
                
            hasta aquí*/
                
                
                
                //En lugar de ésto se crea una consulta para recuperar la fecha más reciente de cualquier AP de tipo 2, que solo se crean a final de curso
                //esto nos dirá si estamos en la ordinaria o la extraordinaria
                    
                $sSQL="select max(fecha) from actuaciones_particulares,actuaciones where actuaciones.cd_actuacion=actuaciones_particulares.tipo and actuaciones.tipo=2 and usu_alumno = '$usuario_alumno' ";
                $sSQL=$sSQL." ORDER BY fecha DESC";		
                //echo $sSQL;
                $contador=0;
                $result=mysqli_query( $conexion, $sSQL);
                while($row=mysqli_fetch_row($result)){
                    if (is_null($row[0])){
                        //como traemos un max(...) si no hay, trae null, y si entra en el while una vez al menos, así que hay que controlarlo con el if is_nul...
                    }else{
                        $fechas[$contador]=$row[0];
                        $contador++;
                    }
                }
                $total_consejos=$contador;

                //recordar que total_consejos ya no contiene el número de consejos sino de actuaciones tipo 2
                //echo "Total consejos: $total_consejos";
                if ($total_consejos>0){
                    echo "<table width=\"100%\"><thead><tr><th>Final de curso:</td></tr><thead>";
                    echo "<tbody><tr><td><ul>";
                    $fecha_consejo="00";
                    for($x=0;$x<$total_consejos;$x++){
                        if ($fecha_consejo<>$fechas[$x]){
                            echo "<form method=\"POST\" action=\"informe_competencias_public.php\" name =\"form_".$x." \">";
                            echo "<li> Informe Final de curso ($fechas[$x])  ";
                            $fecha_consejo=$fechas[$x];
                            //ahora tenemos que saber si es informe de junio o de septiembre para poner un 41 o un 51, para que cargue las notas de la evaluación que sea
                            //cojo la fecha de la actuación, y miro el més, y según eso pongo un 41 o un 51
                            $mes=date("n",strtotime("$fechas[$x]"));
                            $dia=date("j",strtotime("$fechas[$x]"));
                            
                            //CUIDADO, METEMOS A PELO LOS CÓDIGOS 41 Y 51 PARA LAS EVALUACIONES DE CONSEJO ORIENTADOR
                            //Para las siguientes fechas se usa la del sistema, así que no se pueden ver los consejos de la ordinaria o ya el de la extraordinaria
                            //Si el mes es septiembre, está claro que es de extraorinaria
                            if ($mes>=8){
                                    //Si la actuacion de tipo 2 es de septiembre, no hay problema, seguro que es la extraoridinaria y le ponemos un 51.
                                    echo "<input type=\"hidden\" value=\"51\" name=\"cd_evaluacion\" >";				
                            }else{
                                //si la actuación de tipo 2 es de junio, hay que mirar el día...
                                //si es menor que el día 15, le pondremos de la ordinarioa, y si no la extraordinaria
                                if ($dia<15){
                                    echo "<input type=\"hidden\" value=\"41\" name=\"cd_evaluacion\" >";				
                                }else{
                                    echo "<input type=\"hidden\" value=\"51\" name=\"cd_evaluacion\" >";				
                                }
                            }
                            echo "<input type=\"submit\" value=\"Abrir Informe Final de Curso\" name=\"abrir\" >";	
                            echo "</li></form>";				
                        }
                    }		
                    echo "</ul></td></tr></tbody></table>";
                }


            //	$usuario_seleccionado=$_POST["usuario_seleccionado"];
            //	$cd_evaluacion=$_POST["cd_evaluacion"];

            ?>
    </div>
    <br>
    <form method="POST" action="actuaciones_almacenadas.php" target="_blank">Actuaciones almacenadas<br>
        <input type="submit" value="Consultar" name="B1" class="custom-button">
    </form>
    <br>

    <div style="border-width:1px; padding:5px; border-style:outset; position: absolute; width: 567px; height: 496px; z-index: 6; left: 250px; top: 250px;  visibility:hidden" id="capa6">
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
</div>

<?php include("../../componentes/footer.php"); ?>