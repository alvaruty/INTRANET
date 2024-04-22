<?php 
include("../../base.php");
include ("utilities/seguridad_alumnos.php");
include("../../componentes/header.php"); 

error_reporting(0);  // Para ocultar los warnings

//--------Coge las variables de materias.php-------------
$cd_departamento = $_SESSION["cd_departamento"];
$txt_departamento = $_SESSION["txt_departamento"];

//Para mostrar los datos del hijo, comprobamos primero si es padre, y luego le quitamos la letra "p" que debe llevar al principio
$usuario_t=$_SESSION["usuario"];

$permisos=$_SESSION["permisos"];

if ($permisos==0){
    $usuario_alumno=quitaPdePadre($usuario_t);	
} else {
    $usuario_alumno=$usuario_t;	
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis archivos : IES Las Salinas</title>
    <link rel="icon" href="/../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/misArchivos.css">

    <script language="JavaScript">
    <!--
    function lanza_aviso_padres(){
    <?php
    // Consultamos la fecha de la última AP
    $sSQL="select max(fecha)";
    $sSQL=$sSQL."from actuaciones_particulares";
    $sSQL=$sSQL." where usu_alumno = '$usuario_alumno' ";
    $result=mysqli_query( $conexion, $sSQL);
    while($row=mysqli_fetch_row($result)){	
        // Almaceno la fecha de la última AP para avisar a los padres si no la habían visto ya.
        $la_fecha_mas_reciente=$row[0];
    }
    // Consultamos el último acceso de los padres pero no en la tabla de usuarios porque ya se ha modificado
    // Por si no entrán nunca, ponemos 2011-1-1 10:10:10 como ultimo acceso
    $fech ="2011-01-01";
    $hor="10:10:10";

    $f_acceso=$fech." ".$hor;
    // Debemos consultar en la de accesos y coger el segundo más reciente.
    $sSQL="select fecha, hora ";
    $sSQL=$sSQL."from  accesos ";
    $sSQL=$sSQL." where usuario = '".$usuario_t."' order by fecha desc, hora desc";
    $result=mysqli_query( $conexion, $sSQL);
    $contador=0;
    while($row=mysqli_fetch_row($result)){
        if ($contador==1){ // solo cogemos la segunda fecha más reciente porque la más reciente es la de la sesión actual
            // Si la ultima actuacion es más reciente que el ultimo acceso, hacemos que consulte las AP desde el ultimo acceso
            $f_acceso=$row[0]." ".$row[1];
        }
        $contador++;
    }
    if ($f_acceso<$la_fecha_mas_reciente){
        echo "alert(\"Tiene nuevas Actuaciones Particulares.\");";			
        $la_fecha_mas_reciente=$f_acceso;
        echo "capa6.style.visibility='visible';";
        // marcamos de que se va a enviar para que se actualice la tabla de accesos y así no vuelva a salir el mensaje
        $enviado=1;
        // Ahora para que no siga saliendo, inserto un nuevo acceso en la tabla de accesos
        $fech =date("Y-m-d");
        $hor=date("H:i:s");
        $sql="insert into accesos (usuario, fecha, hora) values ('$usuario_t', '$fech', '$hor')";
        $result=mysqli_query($conexion, $sql); 
    }	
    ?>
    }

    </script>
</head>
<body>

    <div class="img">
            <a href="../materias.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    <div class="container">
        <div class="titulo">
            <h2 style="color: #2496ca;">Mis archivos</h2>
        </div>

        <div class="button">
            <a href="ejercicios_online/resolver_ejercicio.php">
                <img src="../../../images/OK.PNG" width="40" height="37">
                <br>Ejercicios test 
            </a>
        </div>

        <div class="table-container">
            <table class="file-table">
                <thead>
                    <tr>
                        <th>Archivos que has enviado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <ul><!-- 141 misdomumentos.php -->
                            <?php
                            $contador=1;
                            $cd_agru=-1;
                            $colorA=dechex(15395583);
                            $colorB='FFFFFF';

                            $result=mysqli_query($conexion, "SELECT cd_agrupacion, txt_agrupacion FROM agrupaciones_de_profesores order by cd_agrupacion");
                            while($row=mysqli_fetch_row($result)){
                                $indice=$row[0];
                                $cd_matriz_agrup[$indice]=$row[1];
                            }
                            echo "Trabajos del departamento ".$txt_departamento.":<br>";
                            $cd_dep=-1;
                            $cd_agrup=-1;
                            $result=mysqli_query($conexion, "SELECT ruta, texto, departamentos.cd_departamento, departamentos.departamento, enlaces_ejercicios.cd_agrupacion FROM enlaces_ejercicios, departamentos where  departamentos.cd_departamento=$cd_departamento and usuario='$usuario_alumno' and enlaces_ejercicios.departamento= departamentos.cd_departamento order by departamentos.cd_departamento, cd_agrupacion, fecha desc");
                            while($row=mysqli_fetch_row($result)){
                                if ($cd_agrup!=$row[4]){
                                    $indice=$row[4];
                                    echo "<h6>Trabajos de la agrupación ".$cd_matriz_agrup[$indice]."($row[4]) :</h6>";
                                    $cd_agrup=$row[4];
                                }	
                                echo "<img border=\"0\" src=\"../../../../images/informe.PNG\" width=\"23\" height=\"23\">";
                                echo "&nbsp<a href=\"$row[0]\">$row[1]</a></br>";	

                            }	
                            echo "<div class=\"separator\"></div>";
                            echo "<h4>Trabajos de OTROS DEPARTAMENTOS:</h4>";


                            $cd_agru=-1;
                            $result=mysqli_query($conexion, "SELECT ruta, texto, departamentos.cd_departamento, departamentos.departamento, enlaces_ejercicios.cd_agrupacion FROM enlaces_ejercicios, departamentos where  departamentos.cd_departamento!=$cd_departamento and usuario='$usuario_alumno' and enlaces_ejercicios.departamento= departamentos.cd_departamento order by departamentos.cd_departamento, cd_agrupacion, fecha desc");
                            while($row=mysqli_fetch_row($result)){
                                if ($cd_dep!=$row[3]){
                                    echo "<br>Trabajos del departamento ".$row[3].":<br>";
                                    $cd_dep=$row[3];
                                }	
                                if ($cd_agrup!=$row[4]){
                                    $indice=$row[4];
                                    if (empty($cd_matriz_agrup[$indice])){
                                        echo "<h6>Trabajos de la agrupación ($indice) :</h6>";
                                    }else{
                                        echo "<h6>Trabajos de la agrupación ".$cd_matriz_agrup[$indice]."($row[4]) :</h6>";
                                    }
                                    $cd_agrup=$row[4];
                                }	
                                echo "<img border=\"0\" src=\"../../../../images/informe.PNG\" width=\"23\" height=\"23\">";
                                echo "&nbsp<a href=\"$row[0]\">$row[1]</a></br>";		
                            }	


                            ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="file-table">
                <thead>
                    <tr>
                        <th>Archivos enviados a ti por los profesores.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <ul>
                            <?php
                            $contador=1;
                            $cd_agru=-1;
                            echo "Archivos de ".$txt_departamento.":<Br>";
                            $cd_dep=-1;
                            $cd_agrup=-1;
                            $result=mysqli_query($conexion, "SELECT ruta, texto, txt_agrupacion,enlaces_profesores.cd_agrupacion FROM enlaces_profesores, agrupaciones_de_profesores where enlaces_profesores.cd_agrupacion= agrupaciones_de_profesores.cd_agrupacion and departamento=$cd_departamento and usuario='$usuario_alumno' order by enlaces_profesores.cd_agrupacion, fecha desc");

                            while($row=mysqli_fetch_row($result)){
                                if ($cd_agrup!=$row[3]){
                                    $indice=$row[3];
                                    echo "<h6>Archivos de la agrupación ".$cd_matriz_agrup[$indice]."($row[3]) :</h6>";
                                    $cd_agrup=$row[3];
                                }	
                                echo "<img border=\"0\" src=\"../../../../images/informe.PNG\" width=\"23\" height=\"23\">";
                                echo "&nbsp<a href=\"$row[0]\">$row[1]</a></br>";	
                            }
                            
                            $cd_dep=-1;
                            $cd_agru=-1;
                            echo "<div class=\"separator\"></div>";
                            echo "<h4>Archivos de OTROS DEPARTAMENTOS:</h4>";
                            
                            $result=mysqli_query($conexion, "SELECT ruta, texto, departamentos.cd_departamento, departamentos.departamento, enlaces_profesores.cd_agrupacion FROM enlaces_profesores, departamentos where departamentos.cd_departamento!=$cd_departamento and usuario='$usuario_alumno' and enlaces_profesores.departamento= departamentos.cd_departamento order by departamentos.cd_departamento, cd_agrupacion, fecha desc");
                            while($row=mysqli_fetch_row($result)){

                                if ($cd_dep!=$row[2]){
                                    echo "<br>Archivos del departamento ".$row[3].":<br>";
                                    $cd_dep=$row[2];
                                }	
                                if ($cd_agrup!=$row[4]){
                                    $indice=$row[4];
                                    echo "<h6>Archivos de la agrupación ".$cd_matriz_agrup[$indice]."($row[4]) :</h6>";
                                    $cd_agrup=$row[4];
                                }	
                                echo "<img border=\"0\" src=\"../../../../images/informe.PNG\" width=\"23\" height=\"23\">";
                                echo "&nbsp<a href=\"$row[0]\">$row[1]</a></br>";		
                            }


                            ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="border-width:1px; padding:5px; border-style:outset; position: absolute; width: 567px; height: 496px; z-index: 6; left: 230px; top: 330px; visibility:hidden" id="capa6">
<table width="100%" Class=ayuda>
<tr>
<td>
Se han realizado las siguientes actuaciones particulares sobre su hijo/a desde su último acceso:<br>


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
	
	<font size="2" face="Consolas">
<img onclick="capa6.style.visibility='hidden';" border="0" id="img9" src="Imagenes/cierraactuacionesnuevas3.jpg" height="20" width="100" alt="Cerrar" onmouseover="FP_swapImg(1,0,/*id*/'img9',/*url*/'Imagenes/cierraactuacionesnuevas1.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img9',/*url*/'Imagenes/cierraactuacionesnuevas3.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img9',/*url*/'Imagenes/cierraactuacionesnuevas2.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img9',/*url*/'Imagenes/cierraactuacionesnuevas1.jpg')" fp-style="fp-btn: Brick Row 4; fp-orig: 0" fp-title="Cerrar"></font>
</ul>
</h4>
</td>
</tr>

</table>
	</div>

    </div>

<?php include("../../componentes/footer.php"); ?>

</body>
