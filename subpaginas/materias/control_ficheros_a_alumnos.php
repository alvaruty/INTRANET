<?php
error_reporting(0);

    include ("../../base.php");
	include ("utilities/seguridad.php");
	$txt_departamento=$_SESSION["txt_departamento"];
	$cd_departamento=$_SESSION["cd_departamento"];
	$usuario_profesor=$_SESSION["usuario"];
	//Recogemos el par?etro que ? mismo se env? para ordenar los ejercicios
	$ordenar_por=$_POST["ordenar_por"];
	$ocultar_corregidos=$_POST["ocultar_corregidos"];

	include("../../componentes/header.php");
?> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir o borrar archivos de alumnos : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/formulario_subida.css">
    <script language="JavaScript">
        function revisa(cd){
            form_registrador.cd_enlace.value =cd;
            form_registrador.accion.value ="1";
            form_registrador.submit();
        }
        function libera(cd){
            form_registrador.cd_enlace.value =cd;
            form_registrador.accion.value ="0";
            form_registrador.submit();
        }
    </script>
    <style>
        #capa2 ul li a:hover {
            text-decoration: underline; 
        }

        #capa2 {
        width: 90%;
        margin: 0 300px;
        text-align: left;
        font-family: Arial, sans-serif;
    }

        .item {
            margin-bottom: 5px;
            padding: 7px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color:#eef8ff;
        }

        .link {
            text-decoration: none;
            color: #2496ca;
            font-size: 14px;
        }

        .agrupacion {
            color: #800000;
            font-size: 12px;
            margin-left: 5px;
        }

        .agrupacion-title {
            font-size: 14px;
            color: #800000;
        }
    </style>
</head>
<body>
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div style="min-height:70%;" class="container">
        <div class="titulo">
            <h2 style="color: #2496ca;">Control de ficheros subidos a alumnos</h2>
            <h4 style="color:gray; font-size:12.5px" >ZONA RESTRINGIDA DE<?php echo " $txt_departamento";?></h4><br>
            <h5 style="color: #2496ca;">Ficheros subidos particularmente a alumnos</h5>
        </div>
        <div style="float:left;">
            <a style="text-decoration:none; color:black" href="formulario_borrar_ficheros_particulares_alumno.php"><br><img border="0" src="../../imagenes/borrarArchivo.png" width="80"><p style="font-size:12px;">Borrar ficheros</a></p><br>
            <form method="POST" action="control_ficheros_a_alumnos.php">
                <p>
                    <select size="1" name="ordenar_por" onchange="submit();">
                        <option value="1">Ordenar por:</option>
                        <option value="1">Agrupación y nombre</option>
                        <option value="2">Fecha</option>
                    </select>
                </p>
                <p>
                    <div style="position: absolute; width: 100px; height: 171px; z-index: 3; visibility:hidden; left:10px; top:204px" id="capa3">
                        &nbsp;
                        <p>&nbsp;<input type="submit" value="Enviar" name="B1"></p>
                    </div>
                </p>
            </form>
        </div>
        <div id="capa2" style="width: 75%; margin: 0 300px; text-align: left; font-family: Arial, sans-serif;">

            <ul style="list-style-type: none; padding-left: 0;">
                <?php
                    // Primero cargamos los nombres de las agrupaciones en una matriz indexada con los códigos
                    $result=mysqli_query($conexion, "SELECT cd_agrupacion, txt_agrupacion, curso FROM agrupaciones_de_profesores where usuario_profesor= '$usuario_profesor' order by curso");
                    while($row=mysqli_fetch_row($result)){
                        $x=$row[0];
                        $cds_agrupaciones[$x]=$row[0];
                        $txt_agrupaciones[$x]=$row[1];
                    }
                    
                    $contador=1;
                    $seccion_clase="o";
                    $fecha = 0;
                    
                    // Seleccionamos los de seccion =99 porque ahí van los archivos subidos de profesores a alumnos.
                    $sSQL="SELECT ruta, texto, curso, clase, fecha, cd_enlace, nombre, apellidos,cd_agrupacion FROM enlaces_profesores, usuarios where enlaces_profesores.`usuario`= usuarios.`usuario` and departamento=$cd_departamento ";
                    
                    $numerador=0;

                    if ($ordenar_por==2){
                        $ordenacion=" ORDER BY fecha desc, cd_agrupacion, clase";
                        $sSQL=$sSQL.$ordenacion;    
                        $result=mysqli_query( $conexion, $sSQL);

                        while($row=mysqli_fetch_row($result)){
                            $x=$row[8];
                            $nombre=$row[6]." ".$row[7];
                            
                            if($fecha!=$row[4]){
                                $fecha = $row[4];
                                $c=$row[2] + 1;                    
                                echo "<li class=\"fecha\"><b><font size=\"2\" color=\"#800000\">$fecha</font></b></li>"
                                    . "<li class=\"item\">"
                                    . "<a href=\"$row[0]\" class=\"link\">".$row[1]."<span class=\"agrupacion\">(".$txt_agrupaciones[$x].")</span>"."($nombre)</a>"
                                    . "</li>";    
                                $numerador++;                
                            }else{
                                echo "<li class=\"item\">"
                                    . "<a href=\"$row[0]\" class=\"link\">".$row[1]."<span class=\"agrupacion\">(".$txt_agrupaciones[$x].")</span>"."($nombre)</a>"
                                    . "</li>";    
                                $numerador++;        
                            }    
                        }
                    }else{
                        $ordenacion=" ORDER BY cd_agrupacion, texto";
                        $sSQL=$sSQL.$ordenacion;    
                        $result=mysqli_query( $conexion, $sSQL);

                        while($row=mysqli_fetch_row($result)){
                            $x=$row[8];
                            $nombre=$row[6]." ".$row[7];
                            
                            if($seccion_clase!=$row[8]){
                                $c=$row[2] + 1;        
                                $agrupacion_text = $txt_agrupaciones[$x] ? $txt_agrupaciones[$x] : "Agrupación de otro profesor";
                                
                                echo "<li class=\"fecha\"><b><font size=\"2\" color=\"#800000\">$agrupacion_text</font></b></li>"
                                    . "<li class=\"item\">"
                                    . "<a href=\"$row[0]\" class=\"link\">".$row[1]."($nombre)</a>"
                                    . "</li>";    
                                $seccion_clase=$row[8];
                                $numerador++;            
                            }else{
                                echo "<li class=\"item\">"
                                    . "<a href=\"$row[0]\" class=\"link\">".$row[1]."($nombre)</a>"
                                    . "</li>";    
                                $numerador++;
                            }    
                        }
                    }
                    
                    mysqli_close($conexion);
                ?>
            </ul>
        </div>
        <div style="position: absolute; width: 100px; height: 100px; z-index: 4; top:500px; visibility:hidden" id="capa5">
            <form method="POST" action="guarda_revision.php" name="form_registrador">
                <p align="center">
                    <input type="submit" value="Enviar" name="B2">
                    <input type="text" name="cd_enlace" value="" size="20">
                    <input type="text" name="ordenar_por" size="20" value="<?php echo $ordenar_por; ?>">
                    <input type="text" name="ocultar_corregidos" size="20" value="<?php echo $ocultar_corregidos; ?>">
                    <input type="text" name="php_de_origen" size="20" value="control_ficheros.php">		
                    <input type="text" name="accion" size="20" value="">		
                </p>
                <p align="center">
                    <input type="reset" value="Restablecer" name="B3">
                </p>
            </form>
            <p>&nbsp;</p>
        </div>


    </div>

<?php
include("../../componentes/footer.php");
?> 