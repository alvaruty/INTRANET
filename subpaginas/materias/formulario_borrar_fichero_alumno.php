<?php
    include ("../../base.php");
    include ("utilities/seguridad.php");
    $txt_departamento=$_SESSION["txt_departamento"];
    $cd_departamento=$_SESSION["cd_departamento"];
    $nivel_permisos=$_SESSION["permisos"];
    $ordenar_por=$_POST["ordenar_por"];
    $ocultar_corregidos=$_POST["ocultar_corregidos"];
    $indice_agrupaciones=$_POST["agrupaciones"];    
    $usuario_profesor=$_SESSION["usuario"];    

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
                highlightRow(campo_a_colorear);
            }
            if (valor==0){
                campocambiado.value=valor;
                campo_a_colorear.value="";
                unhighlightRow(campo_a_colorear);
            }
        }

        function highlightRow(buttonElement) {
            var row = buttonElement.parentElement.parentElement;
            row.classList.add('highlighted');
        }

        function unhighlightRow(buttonElement) {
            var row = buttonElement.parentElement.parentElement;
            row.classList.remove('highlighted');
        }

        function marcar_todos_los_check(){
            var sAux="";
            
            var num=formulario.length;
            for (i=0;i<num;i++)
            {
                sAux = formulario.elements[i].name;
                trozo=sAux.substring(0,8); //ahorramos procesador al comparar solo campo_mo en lugar de campo_modificado.
                if (trozo=='campo_mo'){
                    formulario.elements[i].value=2;
                }
                trozo=sAux.substring(0,2);
                if (trozo=='B4'){
                    formulario.elements[i].value="X";
                    highlightRow(formulario.elements[i]);
                }
            }
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

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #eef8ff;
        }
        
        .custom-table th, .custom-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .custom-table th {
            background-color: #2496ca;
            color: white;
        }

        .custom-table tr:hover {
            background-color: white;
        }

        .highlighted {
            background-color: white; /* Color a elegir para resaltar la fila */
        }
    </style>
</head>
<body>
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div style="min-height:70%;" class="container">
        <div class="titulo">
            <h2 style="color: #2496ca;">ZONA PRIVADA. Departamento de <?php echo " $txt_departamento";?></h2><br>
        </div>
        <div style="float:left; text-align:left">
            <form method="POST" action="formulario_borrar_fichero_alumno.php" name="formulario5">
                <p>&nbsp;</p>
                <p><font color="#000080" face="Tahoma" size="3">Agrupaciones:</font></p>
                <font color="#800080">
                    <?php
                        //cargamos una matriz con todas las agrupaciones del profesor
                        $agrupaciones = 0;
                        //si esta como administrador, verá todas las agrupaciones del departamento, si no, solo las suyas y la opción de todas
                        if ($nivel_permisos == 99) {
                            $sSQL = "SELECT agrupaciones_de_profesores.cd_agrupacion, txt_agrupacion, count(cd_enlace) FROM agrupaciones_de_profesores, enlaces_ejercicios where agrupaciones_de_profesores.cd_agrupacion= enlaces_ejercicios.cd_agrupacion group by cd_agrupacion, txt_agrupacion order by agrupaciones_de_profesores.cd_agrupacion";    
                        } else {
                            $sSQL = "SELECT cd_agrupacion, txt_agrupacion, ' ' FROM agrupaciones_de_profesores where usuario_profesor='$usuario_profesor' order by cd_agrupacion";
                        }   
                        $result = mysqli_query($conexion, $sSQL);
                        while($row = mysqli_fetch_row($result)) {
                            $ind = $agrupaciones;
                            $indice[$ind] = $row[0];
                            $agrupacion[$ind] = $row[1];
                            $total_archivos_agrup[$ind] = $row[2];
                            $agrupaciones++;
                        }
                        //echo "indice agrupaciones= $indice_agrupaciones";
                        echo "<select onchange=\"submit();\" name=\"agrupaciones\" style=\"font-family: Tahoma; font-size: 8pt; color:#000080; margin-bottom:5px;\">";
                        echo "<option value=\"0\"";
                        if($indice_agrupaciones == 0) {
                            echo " selected >";    
                        } else {
                            echo ">";
                        }
                        echo "Todas</option>";
                        for ($i = 0; $i < $agrupaciones; $i++) {
                            echo "<option value=\"$indice[$i]\"";
                            if($indice[$i] == $indice_agrupaciones) {
                                echo " selected >";    
                            } else {
                                echo ">";
                            }
                            echo "$agrupacion[$i]($total_archivos_agrup[$i])</option>";
                        }
                        echo "</select>";
                    ?>
                </font>
                <br>
                <select size="1" name="ordenar_por" onchange="submit();">
                    <option value="1">Ordenar por:</option>   
                    <option value="1"
                    <?php if ($ordenar_por == 1) { echo " selected "; } ?>
                    >Clase y nombre</option>
                    <option value="2"
                    <?php if ($ordenar_por == 2) { echo " selected "; } ?>    
                    >fecha</option>
                </select><br>
                &nbsp;
                <p></p>
                <p>
                    <input type="checkbox" name="ocultar_corregidos" value="ON" 
                    <?php if ($ocultar_corregidos == true) { echo "checked"; } ?>    
                    >Ocultar revisados<br>
                    <input type="submit" value="Recargar" name="B4" style="color: white">
                </p>
                <p><u>
                    <font color="#B24B45" face="Verdana">
                        <?php
                            echo "<br><input onclick=\"marcar_todos_los_check()\" type=\"button\" ";
                            if($indice_agrupaciones == 0) { 
                                echo " disabled=\"true\" ";    
                            }
                            echo " value=\"Marcar Todos\" name=\"marcartodos\" style=\"color: #000080\">";
                        ?>
                    </font>
                </u></p>
                <p>&nbsp;</p>
            </form>
        </div>
        <div style="padding:5px; visibility:visible; margin: 0 200px; text-align: left; width: 90%;" id="capa2">
            <form method="POST" action="borra_ejercicios.php" name="formulario">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="550">Enlace al archivo</th>
                            <th width="100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                                //echo $ordenar_por;
                                $contador = 1;
                                $seccion_clase = "o";
                                $fecha_i = 0;

                                //si estamos como administrador, no enlazamos con la tabla de usuarios, porque puede que ya no esté el profe o el alumno, y el archivo siga.
                                if ($nivel_permisos == 99) {
                                    $sSQL = "SELECT ruta, texto, cd_agrupacion, 'clase', fecha, cd_enlace, revisado, 'nombre', 'apellidos', 'curso' FROM enlaces_ejercicios where ruta<>''  ";
                                } else {
                                    $sSQL = "SELECT ruta, texto, cd_agrupacion, clase, fecha, cd_enlace, revisado, nombre, apellidos, curso FROM enlaces_ejercicios, usuarios where enlaces_ejercicios.`usuario`= usuarios.`usuario`  ";
                                }

                                if ($ocultar_corregidos == true) {
                                    $sSQL = $sSQL . " and revisado is NULL";
                                }

                                if ($indice_agrupaciones > 0) {
                                    $sSQL = $sSQL . " and cd_agrupacion=$indice_agrupaciones ";
                                } else {
                                    $sSQL = $sSQL . " and departamento=$cd_departamento ";
                                }

                                if ($ordenar_por == 2) { //ordena por fecha
                                    if ($nivel_permisos == 99) {
                                        //Si estoy como administrador, no ordeno por nada
                                    } else {
                                        $ordenacion = " order by fecha desc, cd_agrupacion, clase, apellidos";
                                    }
                                    $sSQL = $sSQL . $ordenacion;
                                    $result = mysqli_query($conexion, $sSQL);
                                    while ($row = mysqli_fetch_row($result)) {
                                        $revisado = $row[6];
                                        $clase = $row[3];
                                        $nombre = $row[7] . ' ' . $row[8];
                                        $curso = $row[9];
                                        if ($curso > 3) {
                                            $curso = ($curso - 3) . "º Bacht/ciclo " . chr(65 + $clase);
                                        } else {
                                            $curso = ($curso + 1) . "º " . chr(65 + $clase);
                                        }
                                        if ($revisado != '') {
                                            $revisado = " Revisado: " . $revisado;
                                        } else {
                                            $revisado = "";
                                        }
                                        if ($fecha_i != $row[4]) {
                                            echo "<td bgcolor=\"#F5FAD8\">  $row[4]</td><td></td></tr><tr>";
                                            echo "<td><input type=\"hidden\" name=\"cd$contador\" value = \"$row[5]\">$row[1] ($nombre)<input type=\"hidden\" name=\"ruta$contador\" size=\"40\" value=\"$row[0]\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[5]\">($revisado)</td>";
                                            echo "<td><input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\">";
                                            echo "<input  type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\" style=\"color: #FF0000; font-weight: bold\"></td>";
                                            $contador++;
                                            $fecha_i = $row[4];
                                        } else {
                                            echo "<td><input type=\"hidden\" name=\"cd$contador\" value = \"$row[5]\">$row[1] ($nombre)<input type=\"hidden\" name=\"ruta$contador\" size=\"40\" value=\"$row[0]\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[5]\">($revisado)</td>";
                                            echo "<td><input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\">";
                                            echo "<input  type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\" style=\"color: #FF0000; font-weight: bold\"></td>";
                                            $contador++;
                                        }
                                        echo "</tr><tr>";
                                    }
                                } else {
                                    if ($nivel_permisos == 99) {
                                        //Si estoy como administrador, no ordeno por nada
                                    } else {
                                        $ordenacion = " order by cd_agrupacion, clase,apellidos, nombre, fecha desc ";
                                    }

                                    $sSQL = $sSQL . $ordenacion;
                                    $result = mysqli_query($conexion, $sSQL);
                                    while ($row = mysqli_fetch_row($result)) {
                                        $revisado = $row[6];
                                        if ($revisado != '') {
                                            $revisado = " Revisado: " . $revisado;
                                        } else {
                                            $revisado = "";
                                        }
                                        $clase = $row[3];
                                        $nombre = $row[7] . ' ' . $row[8];
                                        $curso = $row[9];
                                        if ($curso > 3) {
                                            $curso = ($curso - 3) . "º Bacht/ciclo " . chr(65 + $clase);
                                        } else {
                                            $curso = ($curso + 1) . "º " . chr(65 + $clase);
                                        }
                                        if ($seccion_clase != $row[9] . "-" . $row[3]) {
                                            echo "<td  bgcolor=\"#F5FAD8\">$curso</td><td></td></tr><tr>";                
                                            $seccion_clase = $row[9] . "-" . $row[3];
                                            echo "<td><input type=\"hidden\" name=\"cd$contador\" value = \"$row[5]\">$row[1] ($nombre)<input type=\"hidden\" name=\"ruta$contador\" size=\"40\" value=\"$row[0]\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[5]\">($revisado)</td>";
                                            echo "<td><input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\">";
                                            echo "<input  type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\" style=\"color: #FF0000; font-weight: bold\"></td>";
                                            $contador++;
                                        } else {
                                            echo "<td><input type=\"hidden\" name=\"cd$contador\" value = \"$row[5]\">$row[1] ($nombre)<input type=\"hidden\" name=\"ruta$contador\" size=\"40\" value=\"$row[0]\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[5]\">($revisado)</td>";
                                            echo "<td><input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\">";
                                            echo "<input  type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\" style=\"color: #FF0000; font-weight: bold\"></td>";
                                            $contador++;
                                        }
                                        echo "</tr><tr>";
                                    }
                                }
                            ?>
                        </tr>
                    </tbody>
                </table>
                <font color="#000080" size="1">
                    <?php
                        echo "<input type=\"submit\" value=\"Guardar\" name=\"enviar$contador\">";
                        echo "</p><p>Número total de archivos almacenados: <input type=\"text\" name=\"numero_total_archivos\" value = \"$contador\"size=\"8\"></p>";
                        mysqli_close($conexion);    
                    ?>
                </font>
            </form>
        </div>
    </div>
<?php
include("../../componentes/footer.php");
?> 
