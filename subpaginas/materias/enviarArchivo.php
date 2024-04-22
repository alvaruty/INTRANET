<?php 
//error_reporting(0); //quita warnings,
session_start(); 
include("../../base.php");
include("../../componentes/header.php"); 
include ("utilities/seguridad_alumnos_ejercicios.php");// Para saber que tipo de usuario es (necesario)
$usuario_alu=$_SESSION["usuario"];
//--------Coge las variables de materias.php-------------
$cd_departamento = $_SESSION["cd_departamento"];
$txt_departamento = $_SESSION["txt_departamento"];

if (empty($_POST["cd_agrupaciones"])){
    $cd_agrup='00000';
}else{
    $cd_agrup=$_POST["cd_agrupaciones"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar archivo : IES Las Salinas</title>
    <link rel="icon" href="/../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/enviarArchivo.css">
</head>
<body>

<div class="img">
    <a href="../materias.php"><img src="../../imagenes/hacia-atras.png" width="30" height="auto"></a>
</div>

<div class="container">

    <div class="titulo">
        <h2>Envío de ejercicios a profesores de <?php echo "$txt_departamento" ?></h2>
    </div>

    <div class="content">
        <form action="subeejercicio.php" method="post" enctype="multipart/form-data" onsubmit="return FrontPage_Form1_Validator(this)" language="JavaScript" name="FrontPage_Form1" class="form-content"> 
            <p>Selecciona el grupo, materia o profesor a quien envías el ejercicio:</p>
            <p>
                <select name="desplegable_agrupaciones" onchange="frm_recarga.cd_agrupaciones.value=desplegable_agrupaciones.value; frm_recarga.submit();">
                    <option value="o">Selecciona grupo</option>
                    <?php
                    $sSQL= "SELECT alumnos_agrupados.cd_agrupacion, txt_agrupacion FROM agrupaciones_de_profesores, alumnos_agrupados where alumnos_agrupados.usuario= '$usuario_alu' and agrupaciones_de_profesores.cd_agrupacion= alumnos_agrupados.cd_agrupacion order by txt_agrupacion";
                    $result=mysqli_query( $conexion, $sSQL);
                    while($row=mysqli_fetch_row($result)){
                        echo "<option value=\"$row[0]\" onclick =\"B1.disabled=false\" ";
                        if ($cd_agrup==$row[0]){
                            echo " selected ";
                        }
                        echo ">$row[1]</option>";
                    }
                    ?>
                </select>
            </p>

            <p>En esta casilla pon un nombre al ejercicio.</p>
            <p><input name="cadenatexto" size="84" maxlength="80"></p>

            <p>(Localiza en archivo a enviar)<br>
            <b><input name="userfile" type="file"><br>
            <input type="submit" value="Enviar ejercicio"></b></p>
        </form>

        <div class="ayuda-container">
            <div id="capaayuda">
                <table class="ayuda">
                    <tr>
                        <td>
                            <h2>Para enviar los trabajos a los profesores:</h2><br>
                            <p>Selecciona el grupo, materia o profesor al que le envías el trabajo.</p>
                            <p>Localiza el archivo que quieres enviar. Debes tenerlo guardado en alguna unidad de almacenamiento (disco duro, pendrive, cd, dvd,...).</p>
                            <p>Cuidado con el nombre del archivo, no debe contener caracteres extraños ( , ., ª, º, /, ..) es mejor poner únicamente letras, números y espacios. Tampoco puede ser un tamaño muy grande, hasta 80 Mb.</p>
                            <p>, y una vez seleccionado haz clic en Enviar ejercicio y lee el mensaje que te aparecerá a continuación</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <form method="POST" action="enviarArchivo.php" name="frm_recarga">
        <p><input type="hidden" name="cd_agrupaciones" size="20"></p>
    </form>
</div>

</body>
<?php include("../../componentes/footer.php"); ?>

</html>
