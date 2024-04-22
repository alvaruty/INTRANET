<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");
	$usuario_profesor=$_SESSION["usuario"];
	$desplegable_agrupacion=$_POST["desplegable_agrupacion"];
	$txt_departamento=$_SESSION["txt_departamento"];
	$cd_departamento=$_SESSION["cd_departamento"];

	include("../../componentes/header.php");
?> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar archivo : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/formulario_subida.css">
    <script language="JavaScript">
        function envia() {
            formulario1.submit();
        }

        function marcar_todos() {
            var checkboxes = document.getElementsByClassName('checkboxUsuario');
            var marcarTodos = document.getElementById('marcarTodos');

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = marcarTodos.checked;
            }
        }
    </script>
<style>
    .input{
        background-color:#2496ca;
    }
</style>
</head>
<body>
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div style="margin-bottom:12%;" class="container">
        <div class="titulo">
            <h2 style="color: #2496ca;">Envío de archivos a alumnos. Departamento de <?php echo " $txt_departamento";?></h2>
        </div>
        <br><br>
        <div style="float:left;">
            <img border="0" src="../../imagenes/enviar-archivos.png" width="80">
        </div>
        <div style="float:right;">
            <img src="../../../imagenes/ayuda.png" width="70" onmouseover="capa4.style.visibility ='visible';" onmouseout="capa4.style.visibility ='hidden';" >
        </div>
        <div style="clear:both;">
            <form name="formulario1" action="formulario_subida_archivos_multiple_a_alumnos.php" method="post"> 
                <h5 style="text-align:left; margin-top:150px">1- Seleccione la agrupación en la que están el/los alumnos destinatarios del archivo: <select size="1" name="desplegable_agrupacion" onchange="envia();" style="font-weight: 700">                
                    <?php
                    echo "<option value=\"0\" onclick =\"B1.disabled=false\">Seleccione una agrupacion<Br>";
                    //Vamos a recuperar las secciones que cuelgan de la principal
                    //echo "SELECT cd_agrupacion, txt_agrupacion, curso FROM agrupaciones_de_profesores where usuario_profesor= $usuario_profesor order by curso";
                    $result=mysqli_query($conexion, "SELECT cd_agrupacion, txt_agrupacion, curso FROM agrupaciones_de_profesores where usuario_profesor= '$usuario_profesor' order by curso");
                    while($row=mysqli_fetch_row($result)){
                        echo "<option value=\"$row[0]\" onclick =\"B1.disabled=false\">$row[1] ($row[2])<Br>";
                    }
                    echo "</select>";
                ?>	
                <br><br>
                </select></h5>
                <form  name="formulario2" action="sube_archivos_a_alumnos.php" method="POST" enctype="multipart/form-data">
                    <h5 style="text-align:left">2- Introduzca el texto que el alumno verá en sus archivos.&nbsp; <input name="cadenatexto" size="64" maxlength="68"></h5><br>
                    <h5 style="text-align:left">3- Marque los alumnos destinatarios:</h5>
                    <input style="text-align:left" type="checkbox" id="marcarTodos" onclick="marcar_todos();"> (Marcar todos)
                    <?php
                        $clase = -2;  // asigno un valor imposible para que cambie al empezar
                        if ($desplegable_agrupacion == ''){
                            $desplegable_agrupacion = 0;
                        }
                        $sSQL = "SELECT nombre, apellidos, usuarios.curso, clase, usuarios.usuario ";
                        $sSQL .= "FROM usuarios, alumnos_agrupados ";
                        $sSQL .= "WHERE ";
                        $sSQL .= "alumnos_agrupados.cd_agrupacion = $desplegable_agrupacion and ";
                        $sSQL .= "usuarios.usuario = alumnos_agrupados.usuario ";	
                        $sSQL .= "ORDER BY curso, clase, apellidos, nombre ";
                        
                        $contador = 1;	
                        $result = mysqli_query($conexion, $sSQL);

                        // Comprueba si hay algún resultado
                        if(mysqli_num_rows($result) > 0) {
                            echo '<div style="width: 100%; text-align: center;">';
                            echo '<div style="width: auto; margin: 1% 39%; text-align: left;">';

                            while($row = mysqli_fetch_row($result)) {
                                if ($clase != $row[3]) {
                                    $clase = $row[3];
                                    echo "<br>" . chr(65 + $row[3]) . "<br>";	
                                }
                                echo "<input type=\"hidden\" name=\"usuario[]\" value=\"$row[4]\">";
                                echo "<input type=\"checkbox\" class=\"checkboxUsuario\" name=\"check[]\" value=\"$contador\">$row[0] $row[1]<br>";	
                                $contador++;
                            }

                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo "No se encontraron usuarios.";
                        }

                        mysqli_close($conexion);
                    ?>
                    <input type="hidden" name="cd_agrupacion" value="<?php echo $desplegable_agrupacion; ?>">
                    <input type="hidden" name="total_contador" value="<?php echo $contador; ?>">
                    <div style="position: absolute; text-align:left; top:570px">
                    <p>
                        <input name="userfile" type="file">
                    </p><br>
                    <p>
                        <input type="button" value="Enviar Archivo a estos alumnos" name="B3" onclick="submit();" style="background-color: #2496ca; color: #FFFFFF; border:none; padding:3px">
                    </p>
                    <p><br>
                        <input type="reset" value="Deseleccionar alumnos" name="B6" style="background-color: #2496ca; color: #FFFFFF; border:none; padding:3px">
                    </p>

                    </div>
                </form>
                <div style="border-style: outset; border-width: 0px; padding: 5px; position: absolute; width: 265px; height: 282px; z-index: 4; left: 1130px; top: 370px; visibility: hidden" id="capa4">
                    <table width="100%" class="ayuda">
                        <tr>
                            <td>	
                                <br>
                                Localice el archivo que quiere enviar. Debe tenerlo guardado en alguna unidad de almacenamiento (disco duro, pendrive, cd, dvd,...). El nombre del archivo no debe contener caracteres extraños ( , ., ª, º, /, ..) es más seguro poner únicamente letras, números y espacios. Tampoco puede ser un archivo de tamaño muy grande, hasta 80 Mb.<br><br>
                                Una vez seleccionado haga clic en Enviar ejercicio.
                            </td>
                        </tr>
                    </table>
                </div>
        </div>
        

        <div style="position: absolute; width: 100px; height: 100px; z-index: 4; visibility:hidden; left:507px; top:430px" id="capa5">
            <form method="POST" action="guarda_revision.php" name="form_registrador">

                <p align="center"><input type="submit" value="Enviar" name="B2" on>
                <input type="text" name="cd_enlace" value=""size="20">
                <input type="text" name="php_de_origen" size="20" value = "control_ficheros.php">		
                <input type="text" name="accion" size="20" value = "">		
                </p>
                <p align="center"><input type="reset" value="Restablecer" name="B5"></p>
            </form>
            <p>&nbsp;
        </div>
        <form method="POST" action="formulario_envia_tareas_multiple_a_alumnos2.php.php">

            <div style="position: absolute; width: 100px; height: 171px; z-index: 3; visibility:hidden; left:10px; top:204px" id="capa3">
        &nbsp;<p>&nbsp;<input type="submit" value="Enviar" name="B1"></div>
            <p></p>
        </form>
    </div>

<?php
	include("../../componentes/footer.php");
?> 