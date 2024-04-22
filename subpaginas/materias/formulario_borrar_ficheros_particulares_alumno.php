<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");
	$ordenar_por=$_POST["ordenar_por"];
    $txt_departamento=$_SESSION["txt_departamento"];
	$cd_departamento=$_SESSION["cd_departamento"];

	$usuario_profesor=$_SESSION["usuario"];		
	if (empty($_POST["SQLdesde"])){
		$inicioSQL=0;
	}else{
		if ($_POST["SQLdesde"]<0){
			$inicioSQL=0;
		}else{
			$inicioSQL=$_POST["SQLdesde"];
		}	
	}
    include("../../componentes/header.php");
?> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir o borrar archivos de alumnos : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/formulario_subida.css">
    <script language="JavaScript">
        function pasa_valor(campocambiado, campo_a_colorear, valor){
            if (valor==2){
                campocambiado.value=valor;
                campo_a_colorear.value="X";
            }
            if (valor==0){
                campocambiado.value=valor;
                campo_a_colorear.value="";
            }

        }
        function recarga_formulario_cambio_de_ordenacion(){
            formulariosiguientes.ordenar_por.value=frm_ordenacion.ordenar_por.value;
            frm_ordenacion.submit();

        }
    </script>
    <style>
        #capa2 ul li a {
            text-decoration: none; 
            color:#2496ca;
            font-size:13px;
        }
        #capa2 ul li a:hover {
            text-decoration: underline; 
        }
        #capa2{
            text-align:left;
        }

        
    </style>
</head>
<body>
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div style="min-height:70%;" class="container">
        <div class="titulo">
            <h2 style="color: #2496ca;">Borrar archivos subidos a alumnos</h2>
            <h4 style="color: #2496ca; font-size:15px" >Departamento de <?php echo " $txt_departamento";?></h4><br>
        </div>
        <div style="float:left;">
            <img src="../../imagenes/archivos-enviados-alumnos.png" width="90" >
        </div>
        <form method="POST" action="formulario_borrar_ficheros_particulares_alumno.php" name="frm_ordenacion">
            <p>
                <select size="1" name="ordenar_por" onchange="recarga_formulario_cambio_de_ordenacion();">
                    <option value="1">Ordenar por:</option>	
                    <option value="1">Agrupación y apellido (solo se muestran alumnos agrupados por el profesor)</option>
                    <option value="2">Fecha (Se muestran todos los archivos enviados del departamento)</option>
                </select>
            </p>
            <div style="position: absolute; width: 100px; height: 171px; z-index: 3; visibility:hidden; left:1050px; top:287px" id="capa3">
                <input type="submit" value="Enviar" name="B1">
            </div>
            <p></p>
        </form>
        <div id="capa8" style="width: 90%; margin: 0 280px; text-align: left;>
            <table width="100%">
                <thead>
                    <form name="formulariosiguientes" method="POST" action="formulario_borrar_ficheros_particulares_alumno.php">
                        <tr>
                            <th><br>Listado actual de archivos enviados:    
                                <?php
                                    echo "<input type=\"hidden\" name=\"ordenar_por\" value=\"$ordenar_por\">";
                                    echo "<input type=\"hidden\" name=\"SQLdesde\" value=\"\">";
                                    echo "<input type=\"button\" value=\"&lt;-\" name=\"anteriores\" onmouseover=\"SQLdesde.value='".($inicioSQL-25)."'\" onclick=\"submit();\">";
                                    echo "  $inicioSQL-".($inicioSQL+25);
                                    echo "  <input type=\"button\" value=\"-&gt;\" name=\"siguienes\" onmouseover=\"SQLdesde.value='".($inicioSQL+25)."'\" onclick=\"submit();\">";
                                ?>
                            </th>
                        </tr>
                    </form>
                </thead>
                <tbody>	
                    <tr>
                        <td>
                            <ul>
                                <form name="formulario" method="POST" action="borra_archivos_particulares.php">
                                    <?php
                                        $contador=1;
                                        $lista_agrupaciones="(0";  //preparamos un listado de codigos de agrupaciones para luego hacer una consulta con un in (...).
                                        
                                        //primero cargamos los nombres de las agrupaciones en una matiz indexada con los códigos
                                        $result=mysqli_query($conexion, "SELECT cd_agrupacion, txt_agrupacion, curso FROM agrupaciones_de_profesores where usuario_profesor= '$usuario_profesor' order by curso");
                                        while($row=mysqli_fetch_row($result)){
                                            $x=$row[0];
                                            $cds_agrupaciones[$x]=$row[1];
                                            $lista_agrupaciones=$lista_agrupaciones.",".$x;
                                        }
                                        $lista_agrupaciones=$lista_agrupaciones.")";
                                        $contador=1;
                                        $numero_registros=0;
                                        $seccion_clase="o";
                                        $sSQL="SELECT ruta, texto, curso, clase, fecha, cd_enlace, nombre, apellidos,cd_agrupacion FROM enlaces_profesores, usuarios where enlaces_profesores.`usuario`= usuarios.`usuario` and departamento=$cd_departamento ";
                                        $numerador=0;
                                        
                                        if ($ordenar_por==2){
                                            $ordenacion=" order by fecha desc, cd_agrupacion, clase limit $inicioSQL, 25";
                                            $sSQL=$sSQL.$ordenacion;	
                                            $result=mysqli_query($conexion, $sSQL);
                                            while($row=mysqli_fetch_row($result)){
                                                echo "<li>";
                                                $x=$row[8];
                                                $nombre=$row[6]." ".$row[7];
                                                if($fecha!=$row[4]){
                                                    $fecha = $row[4];
                                                    $c=$row[2] + 1;	
                                                    echo "<Br><b><font size=\"2\" color=\"#800000\">( $fecha )</font></b><Br>";				
                                                    echo "<input disabled type=\"text\" name=\"nombre$contador\" size=\"35\" value=\"$row[6] $row[7] \">";
                                                    echo "<input type=\"hidden\" name=\"cd_enlace$contador\" value=\"$row[0]\"><input disabled type=\"text\" name=\"usuario$contador\" size=\"40\" value=\"$row[1]\"><input type=\"hidden\" name=\"ruta$contador\" size=\"40\" value=\"$row[0]\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[5]\">";
                                                    echo "<input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\">";
                                                    echo "<input type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\" style=\"color: #FF0000; font-weight: bold\"><br>";		
                                                    $numero_registros++;
                                                    $contador++;
                                                }else{
                                                    echo "<input disabled type=\"text\" name=\"nombre$contador\" size=\"35\" value=\"$row[6] $row[7] \">";	
                                                    echo "<input type=\"hidden\" name=\"cd_enlace$contador\" value=\"$row[0]\"><input disabled type=\"text\" name=\"usuario$contador\" size=\"40\" value=\"$row[1]\"><input type=\"hidden\" name=\"ruta$contador\" size=\"40\" value=\"$row[0]\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[5]\">";
                                                    echo "<input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\">";
                                                    echo "<input type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\" style=\"color: #FF0000; font-weight: bold\"><br>";
                                                    $numero_registros++;
                                                    $contador++;	
                                                }
                                                echo "</li>";		
                                            }	//fin del while
                                        }else{
                                            $ordenacion=" and cd_agrupacion in $lista_agrupaciones order by cd_agrupacion, apellidos limit $inicioSQL, 25";
                                            $sSQL=$sSQL.$ordenacion;
                                            //echo 	$sSQL;
                                            $result=mysqli_query($conexion, $sSQL);
                                            while($row=mysqli_fetch_row($result)){
                                                echo "<li>";		
                                                $x=$row[8];
                                                $nombre=$row[6]." ".$row[7];
                                                if($seccion_clase!=$row[8]){
                                                    $c=$row[2] + 1;	
                                                    echo "<Br><font size=\"2\" color=\"#800000\">$cds_agrupaciones[$x]</font><Br>";	
                                                    echo "<input disabled type=\"text\" name=\"nombre$contador\" size=\"35\" value=\"$row[6] $row[7] \">";
                                                    echo "<input type=\"hidden\" name=\"cd$contador\" value=\"$row[0]\"><input disabled type=\"text\" name=\"usuario$contador\" size=\"40\" value=\"$row[1]\"><input type=\"hidden\" name=\"ruta$contador\" size=\"40\" value=\"$row[0]\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[5]\">";
                                                    echo "<input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\">";
                                                    echo "<input type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\" style=\"color: #FF0000; font-weight: bold\"><br>";		
                                                    $numero_registros++;
                                                    $contador++;
                                                    $seccion_clase=$row[8];
                                                }else{
                                                    echo "<input disabled type=\"text\" name=\"nombre$contador\" size=\"35\" value=\"$row[6] $row[7] \">";	
                                                    echo "<input type=\"hidden\" name=\"cd$contador\" value=\"$row[0]\"><input disabled type=\"text\" name=\"usuario$contador\" size=\"40\" value=\"$row[1]\"><input type=\"hidden\" name=\"ruta$contador\" size=\"40\" value=\"$row[0]\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[5]\">";
                                                    echo "<input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\">";
                                                    echo "<input type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\" style=\"color: #FF0000; font-weight: bold\"><br>";
                                                    $numero_registros++;
                                                    $contador++;	
                                                }	
                                                echo "</li>";					
                                            }
                                        }	
                                        
                                        echo "<input type=\"submit\" value=\"Borrar\" name=\"enviar$contador\">";
                                        echo "<input type=\"hidden\" name=\"numero_total_archivos\" value=\"$numero_registros\" size=\"8\">";
                                        //mysqli_close($conexion); 
                                        echo "</p><p>Número total de archivos almacenados: $numero_registros";
                                        echo "<input type=\"hidden\" name=\"numero_total_archivos\" value=\"$numero_registros\" size=\"8\"></p>";
                                        mysqli_close($conexion); 
                                    ?>
                                </form>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="border-style: outset; border-width: 0px; padding: 5px; position: absolute; width: 300px; height: 73px; z-index: 6; left: 126px; top: 411px;" id="capa8">
            <table width="100%" class="ayuda">
                <tr>
                    <td>
                        <p>1- Seleccione los archivos que desea borrar haciendo clic en X.</p>
                        <p>2- Puede deseleccionar los archivos marcados haciendo clic en B.</p>
                        <p>3- Las marcas no se guardan al avanzar en la paginación.</p>
                        <p>4- Haga clic en Borrar para proceder a la eliminación de los archivos seleccionados.</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php
include("../../componentes/footer.php");
?> 