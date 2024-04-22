<?php 
include("../../base.php");
include ("utilities/seguridad_alumnos.php");

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
    <title>Actuaciones almacenadas : IES Las Salinas</title>
    <link rel="icon" href="/../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/actuaciones.css">
</head>
<body>
<div class="container">
    <div class="titulo">
        <h2 style="color: #2496ca;">Actuaciones particulares almacenadas</h2>
    </div>

    <div class="table-container">
        <table class="file-table">
            <thead>
                <tr>
                    <th>Actuaciones particulares almacenadas con:<?php 

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
                </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <ul>
                        <?php
                            $sSQL="select actuacion , usu_alumno , fecha, nombre, apellidos,comentario, comentario_visible ";
                            $sSQL=$sSQL."from actuaciones_almacenadas, actuaciones, usuarios ";
                            $sSQL=$sSQL." where usu_alumno = '$usuario_alumno' and ";
                            $sSQL=$sSQL." usuarios.usuario = actuaciones_almacenadas.usu_profesor and ";
                            $sSQL=$sSQL." actuaciones.cd_actuacion=actuaciones_almacenadas.tipo order by  fecha desc";
                            //echo $sSQL;
                            $result=mysqli_query( $conexion, $sSQL);
                            while($row=mysqli_fetch_row($result)){
                                echo "<li>";	
                                echo  "$row[0]: <span>$row[5]</span>";
                                if ($row[6]==1){echo "&nbsp;&nbsp;&nbsp;&nbsp;(año-mes-dia: $row[2]) <span>(Profesor: $row[3] $row[4]) </span>";}				
                                echo "</li>";
                            }
                            mysqli_close($conexion); 	
                        ?>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        
</table>
	</div>
</div>
