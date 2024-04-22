<?php 
include ("utilities/seguridad_comunicacion.php"); //luego quitar comentario
session_start(); 
include("../../base.php");
include("../../componentes/header.php"); 

$remitente=$_SESSION["usuario"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunicaciones : IES Las Salinas</title>
    <link rel="icon" href="../../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/comunicaciones.css">

    <script type="text/javascript" language="JavaScript1.2">
        function muestra_comunicaciones(){
        <?php
            $fecha_hoy=date("Y-m-d");
            $sSQL="SELECT tipos_comunicaciones.texto,comunicaciones.texto, nombre, apellidos, fecha, hora ";
            $sSQL=$sSQL." FROM tipos_comunicaciones, comunicaciones, usuarios ";
            $sSQL=$sSQL." where tipos_comunicaciones.cd_comunicacion= comunicaciones.cd_comunicacion and";
            $sSQL=$sSQL." comunicaciones.usuario_destino= usuarios.usuario and";
            $sSQL=$sSQL." comunicaciones.usuario_remitente= '$remitente' ";	
            $sSQL=$sSQL." order by fecha desc, hora desc ";	
            //echo $sSQL;
            $result=mysqli_query( $conexion, $sSQL);
            while($row=mysqli_fetch_row($result)){
                if ($row[4]==$fecha_hoy){
                    echo "alert('Hoy ya ha realizado la siguiente comunicación: $row[4] ($row[5]): $row[0] con $row[2] $row[3] ($row[1])');";
                }
            }
        ?>
        }

    </script>

</head>
<body onload =" muestra_comunicaciones();">

<script>
    function FrontPage_Form1_Validator(theForm)
    {

    if (theForm.desplegable_profesores.selectedIndex < 0)
    {
        alert("Elija una de las opciones \"desplegable_profesores\".");
        theForm.desplegable_profesores.focus();
        return (false);
    }

    if (theForm.desplegable_profesores.selectedIndex == 0)
    {
        alert("La primera opción \"desplegable_profesores\" no es válida. Elija una de las otras opciones.");
        theForm.desplegable_profesores.focus();
        return (false);
    }
    return (true);
    }
</script>

<div class="img">
    <a href="../materias.php"><img src="../../imagenes/hacia-atras.png" width="30" height="auto"></a>
</div>

<div class="container">

    <div class="titulo">
        <h2>Comunicación de PADRES con PROFESORES</h2>
    </div>

    <div class="content">
        <form method="POST" action="guarda_comunicacion.php" onsubmit="return FrontPage_Form1_Validator(this)" language="JavaScript" name="FrontPage_Form1" class="form-content">
            <p>Seleccione el profesor al que quiere enviar la comunicación:</p>
            <p>
                <select name="desplegable_agrupaciones">
                    <option value="o">Profesores</option>
                    <?php
                    $result=mysqli_query($conexion, "SELECT apellidos, nombre, usuario FROM usuarios where nivel_permisos >1 and nivel_permisos < 99 order by apellidos, nombre");
                    while($row=mysqli_fetch_row($result)){
                        echo "<option value=".$row[2].">".$row[1]." ".$row[0]."</option>"  ;	
                    }
                    ?> 
                </select>
            </p>

            <p>Seleccione el tipo de comunicación que desea realizar a dicho profesor:</p>
            <?php
                $result=mysqli_query($conexion, "SELECT texto, cd_comunicacion FROM tipos_comunicaciones order by cd_comunicacion ");
                while($row=mysqli_fetch_row($result)){
                    echo "<input checked type=\"radio\" value=\"$row[1]\" name=\"tipo_comunicacion\">$row[0]<br>"  ;	
                }
            ?> 
            <br>
            <p>Asunto <font size="2">(Máximo 250 caracteres)</font></p>
            <p><input type="text" name="texto_comunicacion" size="90" maxlength="250"></p>

            <p><input type="submit" value="Enviar" name="B1"></p>
        </form>
        <p style="color:#000080">Historial de comunicaciones realizadas con profesores:</p>
        <?php
            $sSQL="SELECT tipos_comunicaciones.texto,comunicaciones.texto, nombre, apellidos, fecha, hora ";
            $sSQL=$sSQL." FROM tipos_comunicaciones, comunicaciones, usuarios ";
            $sSQL=$sSQL." where tipos_comunicaciones.cd_comunicacion= comunicaciones.cd_comunicacion and";
            $sSQL=$sSQL." comunicaciones.usuario_destino= usuarios.usuario and";
            $sSQL=$sSQL." comunicaciones.usuario_remitente= '$remitente' ";	
            $sSQL=$sSQL." order by fecha desc, hora desc ";	
            //echo $sSQL;
            $result=mysqli_query( $conexion, $sSQL);
            while($row=mysqli_fetch_row($result)){
                echo "* $row[4] ($row[5]): $row[0] con $row[2] $row[3] ($row[1])<br>"  ;	
            }
            mysqli_close($conexion);
        ?> 
    </div>
    
</div>

</body>
<?php include("../../componentes/footer.php"); ?>

</html>
