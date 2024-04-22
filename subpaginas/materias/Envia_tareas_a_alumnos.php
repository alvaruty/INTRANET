<?php
    include ("../../base.php");
    include ("utilities/seguridad.php");
    $usuario_profesor = $_SESSION["usuario"];
    if (empty($_POST["desplegable_agrupacion"])) {
        $desplegable_agrupacion = 0;
    } else {
        $desplegable_agrupacion = $_POST["desplegable_agrupacion"];
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
        function envia() {
            formulario1.submit();
        }

        function marcar_todos() {
            var checkboxes = document.getElementsByClassName('checkboxAlumno');
            var marcarTodos = document.getElementById('marcarTodos');

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = marcarTodos.checked;
            }
        }
    </script>
</head>
<body onload="muestra_capa();">
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div style="min-height:56%;" class="container">
        <div class="titulo">
            <h2 style="color: #2496ca;">Enviar tareas a alumnos</h2>
        </div>
        <form style="text-align:left;" name="formulario1" action="Envia_tareas_a_alumnos.php" method="post"> 
            <br><br>
            <h4 style="color:2496ca;">Enviar una nueva tarea</h4><br>
            <h5>1- Seccione la agrupación en la que están el/los alumnos destinatarios del archivo:
            
            <select size="1" name="desplegable_agrupacion" onchange="envia();" >
        <?php
            echo "<option value=\"0\" onclick =\"B1.disabled=false\">Seleccione una agrupacion<Br>";
            $result=mysqli_query($conexion, "SELECT cd_agrupacion, txt_agrupacion, curso FROM agrupaciones_de_profesores where usuario_profesor= '$usuario_profesor' order by curso");
            while($row=mysqli_fetch_row($result)){
                echo "<option value=\"$row[0]\" onclick =\"B1.disabled=false\"";
                if ($desplegable_agrupacion==$row[0]){ echo " selected ";}
                echo ">$row[1] ($row[2])</option>";
            }
        ?>	
            </select></h5>
        </form><br>
        <form style="text-align:left;" name="formulario2" action="sube_tareas_a_alumnos.php" method="POST" enctype="multipart/form-data">
            <h5>2- Introduzca el texto que el alumno verá en sus tareas.
                <input name="cadenatexto" size="58" maxlength="68">
            </h5>

            <h6>&nbsp;</h6>
            <h5>3 -Seleccione la Actividad evaluable que desee asociar a esta tarea (de tipo digital) creadas para esta agrupación.</h5>
            (Al alumno le aparecerá una tarea pendiente de realizar, con fecha de entrega máxima igual a la de la Actividad evaluable)<br>
            &nbsp;
            <select size="1" name="desplegable_actividades">
                <?php
                    echo "<option value=\"0\" >Actividades evaluables creadas para la agrupación";
                    $result=mysqli_query($conexion, "select cd_actividad, txt_actividad, fecha, evaluacion from actividades where cd_agrupacion=$desplegable_agrupacion and tipo >0 order by evaluacion");
                    while($row=mysqli_fetch_row($result)){
                        echo "<option value=\"$row[0]\" onclick =\"B1.disabled=false\">$row[1] ($row[3]ª Eva. $row[2])";
                    }
                    echo "</select>";
                ?>	
            <br> 
            </select>
            <br>
            <h5>4- Marque los alumnos destinatarios de la tarea:</h5> 
            <input type="checkbox" id="marcarTodos" onclick="marcar_todos();"> (Marcar todos)
            <?php
                $clase_alu = -2;
                if ($desplegable_agrupacion==''){
                    $desplegable_agrupacion=0;
                }
                $sSQL="SELECT  nombre, apellidos, usuarios.curso, clase, usuarios.usuario ";
                $sSQL=$sSQL."FROM usuarios, alumnos_agrupados ";
                $sSQL=$sSQL."where ";
                $sSQL=$sSQL." alumnos_agrupados.cd_agrupacion = $desplegable_agrupacion and " ;
                $sSQL=$sSQL."usuarios.usuario = alumnos_agrupados.usuario ";	
                $sSQL=$sSQL."order by curso, clase, apellidos, nombre ";
                $contador=1;	
                $result=mysqli_query( $conexion, $sSQL);
                while($row=mysqli_fetch_row($result)){
                    if ($clase_alu !=$row[3]){
                        $clase_alu =$row[3];
                        echo  "<Br>".chr(65+$row[3])."<Br>";	
                    }	
                    echo "<input type=\"hidden\" name=\"usuario$contador\" value=\"$row[4]\"><input type=\"checkbox\" class=\"checkboxAlumno\" name=\"check$contador\" >$row[0] $row[1]<Br>";	
                    $contador=$contador+1;
                }
            ?>
            <div style="position:absolute; top:450px; left:700px">
                <input type="hidden" name="cd_agrupacion" value="<?php echo $desplegable_agrupacion; ?>">
                <input type="hidden" name="total_contador" value="<?php echo $contador; ?>">
                </p>
                <p style="text-align: center">
                    <input name="userfile" type="file" style="float: left"><br>
                </p><br>
                <p>
                    <input type="button" value="Enviar Archivo a estos alumnos" name="B5" onclick="submit();" style="color: white; background-color:#2496ca; border:none; padding:2px;" >
                </p><br>
                <p>
                    <input type="reset" value="Deseleccionar alumnos" name="B6" style="color: white; background-color:#2496ca; border:none; padding:2px;">
                </p>
            </div>
        </form>
        <div style="border-style: outset; border-width: 0px; padding: 5px; position: absolute; width: 365px; height: 282px; z-index: 4; left: 1130px; top: 270px; visibility: hidden" id="capa4">
                    <table width="100%" class="ayuda">
                        <tr>
                            <td>	
                                Localice el archivo que quiere enviar. Debe tenerlo guardado en 
                                alguna unidad de almacenamiento (disco duro, pendrive, cd, dvd,...). El nombre del archivo no 
                                debe contener caracteres extraños ( , ., ª, º, /, ..)&nbsp; es más seguro 
                                poner únicamente letras, números y espacios . Tampoco puede ser un archivo 
                                de tamaño muy grande, hasta 80 Mb.<br>Debe relacionar el archivo con alguna actividad evaluable creada, y 
                                le aparecerá al alumno como pendiente de realizar en su perfil.<br>
                                Una vez seleccionado haga clic en Enviar ejercicio.                            </td>
                        </tr>
                    </table>
                </div>

    </div>
<?php
include("../../componentes/footer.php");
?> 
