<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");
	$txt_departamento=$_SESSION["txt_departamento"];
	$cd_departamento=$_SESSION["cd_departamento"];
	$usuario_profesor=$_SESSION["usuario"];
	//Recogemos el par?etro que ? mismo se env? para ordenar los ejercicios
	$ordenar_por=$_POST["ordenar_por"];
	$ocultar_corregidos=$_POST["ocultar_corregidos"];
	$indice_agrupaciones=$_POST["agrupaciones"];

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
        #capa2 ul li a {
            text-decoration: none; 
            color:#2496ca;
            font-size:14px;
        }
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
            margin-bottom: 10px;
            padding: 5px;
            border-bottom: 1px solid #e0e0e0;
        }

        .button {
            font-size: 10px;
            margin-right: 5px;
            padding: 3px 8px;
            cursor: pointer;
            background-color: #2496ca;
            border: none;
            color: white;
            border-radius: 3px;
        }

        .link {
            text-decoration: none;
            color: #2496ca;
            font-size: 14px;
        }
</style>
    </style>
</head>
<body>
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="40" height=auto></a>
    </div>
    
    <div style="min-height:70%;" class="container">
        <div class="titulo">
            <h2 style="color: #2496ca;">Control de archivos subidos por alumnos</h2>
            <h4 style="color:gray; font-size:12.5px" >ZONA RESTRINGIDA DE<?php echo " $txt_departamento";?></h4><br>
        </div>
        <div style="float:left;">
            <a style="text-decoration:none; color:black" href="formulario_borrar_fichero_alumno.php"><br><img border="0" src="../../imagenes/borrarArchivo.png" width="70"><p style="font-size:12px;">Borrar trabajo recibido</a></p><br>
            <form method="POST" action="control_ficheros.php" name="formulario5">
                <br><h4 style="color:#2496ca">Mis Agrupaciones:</h4>
                <span style="font-weight: 300">
                    (Si elige Todas, solo<br>
                    muestran los archivos<br>
                    enviados a este<br>
                    departamento.)<br>
                </span><br>
                <?php
                    //cargamos una matriz con todas las agrupaciones del profesor
                    $agrupaciones = 0;
                    $sSQL = "SELECT cd_agrupacion, txt_agrupacion FROM agrupaciones_de_profesores where usuario_profesor='$usuario_profesor' order by cd_agrupacion";
                    $result = mysqli_query($conexion, $sSQL);

                    while ($row = mysqli_fetch_row($result)) {
                        $ind = $agrupaciones;
                        $indice[$ind] = $row[0];
                        $agrupacion[$ind] = $row[1];
                        $agrupaciones++;
                    }

                    echo "<select onchange=\"submit();\" name=\"agrupaciones\" style=\"font-family: Tahoma; font-size: 8pt; color:#000080\">";
                    echo "<option value=\"0\"";
                    
                    if ($indice_agrupaciones == 0) {
                        echo " selected>";	
                    } else {
                        echo ">";
                    }
                    
                    echo "Todas</option>";

                    for ($i = 0; $i < $agrupaciones; $i++) {
                        echo "<option value=\"$indice[$i]\"";
                        
                        if ($indice[$i] == $indice_agrupaciones) {
                            echo " selected>";	
                        } else {
                            echo ">";
                        }
                        
                        echo "$agrupacion[$i] ($indice[$i])</option>";
                    }

                    echo "</select>";
                ?>
                <p><br>
                    <input type="checkbox" name="ocultar_corregidos" value="ON" 
                        <?php if ($ocultar_corregidos == true) { echo "checked"; } ?>	
                    >
                    Ocultar revisados
                </p>
                <select size="1" name="ordenar_por" onchange="submit();">
                    <option value="1">Ordenar por:</option>	
                    <option value="1"
                        <?php if ($ordenar_por == 1) { echo " selected "; } ?>
                    >
                        Clase y nombre
                    </option>
                    <option value="2"
                        <?php if ($ordenar_por == 2) { echo " selected "; } ?>	
                    >
                        Fecha
                    </option>
                </select><br>
                <input type="submit" value="Buscar" name="B4" style="color: white"></p>
            </form>
        </div>
        <div id="capa2" style="width: 90%; margin: 0 300px; text-align: left; font-family: Arial, sans-serif;">

            <ul style="list-style-type: none; padding-left: 0;">
                <?php
                    $contador = 1;
                    $seccion_clase = "o";
                    $fecha_i = 0;

                    $sSQL = "SELECT ruta, texto, cd_agrupacion, clase, fecha, cd_enlace, revisado, nombre, apellidos, curso FROM enlaces_ejercicios, usuarios where enlaces_ejercicios.`usuario`= usuarios.`usuario` ";

                    if ($ocultar_corregidos == true) {
                        $sSQL .= " and revisado is NULL";
                    }

                    if ($indice_agrupaciones > 0) {
                        $sSQL .= " and cd_agrupacion=$indice_agrupaciones ";
                    } else {
                        $sSQL .= " and departamento=$cd_departamento ";
                    }

                    $numerador = 0;

                    if ($ordenar_por == 2) {
                        $ordenacion = " ORDER BY fecha desc, cd_agrupacion, clase, apellidos";
                        $sSQL .= $ordenacion;	
                        $result = mysqli_query($conexion, $sSQL);

                        while ($row = mysqli_fetch_row($result)) {
                            $revisado = $row[6];
                            $fecha = $row[4];	
                            $clase = $row[3];					
                            $curso = ($row[9] + 1) . "º " . chr(65 + $clase);			

                            if ($revisado != '') {
                                $revisado = " Revisado: " . $revisado;
                            } else {
                                $revisado = "";
                            }

                            if ($fecha_i != $row[4]) {
                                echo "<li class=\"item\" style=\"margin-bottom: 10px;\">"
                                    . "<button class=\"button\" name=\"M$numerador\" onclick=\"revisa($row[5]);\">M</button>"
                                    . "<button class=\"button\" name=\"L$numerador\" onclick=\"libera($row[5]);\">L</button>"
                                    . "<a href=\"$row[0]\" target=\"_blank\" class=\"link\">".$row[7]." ".$row[8].": ".$row[1]."(".$curso.")--".$revisado."</a>"
                                    . "</li>";	
                                $numerador++;			
                                $fecha_i = $row[4];	
                            } else {
                                echo "<li class=\"item\" style=\"margin-bottom: 10px;\">"
                                    . "<button class=\"button\" name=\"M$numerador\" onclick=\"revisa($row[5]);\">M</button>"
                                    . "<button class=\"button\" name=\"L$numerador\" onclick=\"libera($row[5]);\">L</button>"
                                    . "<a href=\"$row[0]\" target=\"_blank\" class=\"link\">".$row[7]." ".$row[8].": ".$row[1]."(".$curso.")--".$revisado."</a>"
                                    . "</li>";	
                                $numerador++;		
                            }	
                        }
                    } else {
                        $ordenacion = " ORDER BY cd_agrupacion, clase, apellidos, nombre, fecha desc ";
                        $sSQL .= $ordenacion;	
                        $result = mysqli_query($conexion, $sSQL);

                        while ($row = mysqli_fetch_row($result)) {
                            $revisado = $row[6];
                                        
                            if ($revisado != '') {
                                $revisado = " Revisado: " . $revisado;
                            } else {
                                $revisado = "";
                            }

                            $clase = $row[3];
                            $curso = ($row[9] + 1) . "º " . chr(65 + $clase);			

                            if ($seccion_clase != $row[9] . "-" . $row[3]) {
                                echo "<li class=\"item\" style=\"margin-bottom: 10px;\">"
                                    . "<button class=\"button\" name=\"M$numerador\" onclick=\"revisa($row[5]);\">M</button>"
                                    . "<button class=\"button\" name=\"L$numerador\" onclick=\"libera($row[5]);\">L</button>"
                                    . "<a href=\"$row[0]\" target=\"_blank\" class=\"link\">".$row[7]." ".$row[8].": ".$row[1]."--".$revisado."</a>"
                                    . "</li>";	
                                $seccion_clase = $row[9] . "-" . $row[3];
                                $numerador++;			
                            } else {
                                echo "<li class=\"item\" style=\"margin-bottom: 10px;\">"
                                    . "<button class=\"button\" name=\"M$numerador\" onclick=\"revisa($row[5]);\">M</button>"
                                    . "<button class=\"button\" name=\"L$numerador\" onclick=\"libera($row[5]);\">L</button>"
                                    . "<a href=\"$row[0]\" target=\"_blank\" class=\"link\">".$row[7]." ".$row[8].": ".$row[1]."--".$revisado."</a>"
                                    . "</li>";	
                                $numerador++;
                            }	
                        }
                    }

                    mysqli_close($conexion);	
                ?>
            </ul>
        </div>
        <div style="position: absolute; width: 100px; height: 100px; z-index: 4; left:10px; top:300px; visibility:hidden" id="capa5">
            <form method="POST" action="guarda_revision.php" name="form_registrador">

                    <p align="center"><input type="submit" value="Enviar" name="B2" on>
                    <input type="text" name="cd_enlace" value=""size="20">
                    <input type="text" name="ordenar_por" size="20" value="<?php echo $ordenar_por; ?>">
                    <input type="text" name="agrupaciones" size="20" value="<?php echo $indice_agrupaciones; ?>">
                    <input type="text" name="ocultar_corregidos" size="20" value = "<?php echo $ocultar_corregidos; ?>">
                    <input type="text" name="php_de_origen" size="20" value = "control_ficheros.php">		
                    <input type="text" name="accion" size="20" value = "">		
                    </p>
                    <p align="center"><input type="reset" value="Restablecer" name="B3"></p>
                </form>
                <p>&nbsp;
        </div>

    </div>
<?php
include("../../componentes/footer.php");
?> 