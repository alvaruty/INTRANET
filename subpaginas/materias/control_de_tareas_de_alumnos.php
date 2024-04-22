<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");
	$usuario_profesor=$_SESSION["usuario"];
	if (empty($_POST["desplegable_agrupacion"])){
		$desplegable_agrupacion=0;
	}
	else{
		$desplegable_agrupacion=$_POST["desplegable_agrupacion"];
	}

	include("../../componentes/header.php");
?> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de tareas : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/formulario_subida.css">
    <script>
        function envia(){
            formulario1.submit();

        }

        function muestra_capa(){
        <?php
            if ($desplegable_agrupacion>0){
                echo "capa16.style.visibility='visible';";	
            }else{
                echo "capa16.style.visibility='hidden';";	
            }
        ?>
        }

        function confirma_borrado(){
        //	alert("llega ");
            if(confirm("¿Está seguro de que quiere BORRAR ESOS ARCHIVOS DE TAREAS?")){
                formu.submit();
            }

        }


        function oculta_desplegar(botDespl,botRecog, capa){
            botDespl.style.display='none';
            botRecog.style.display='block';
            capa.style.display= 'block';
        }
        function oculta_recoger(botDespl,botRecog, capa){
            botDespl.style.display='block';
            botRecog.style.display='none';
            capa.style.display= 'none';
        }

        //marcamos todas para crear actuación con los datos de las marcas.
        function marcar_todos(){
            var sAux="";
            var num=formulario2.length;
            marca=1;
            if (formulario2.paraenviartodas.checked==0){
                marca=0;
            }
            for (i=0;i<num;i++)
            {
                sAux = formulario2.elements[i].name;
                trozo=sAux.substring(0,5);
                if (trozo=='check'){
                        formulario2.elements[i].checked=marca;
                }
            }
        }

        function pasa_valor(campocambiado, campo_a_colorear, valor){
        //	alert(campocambiado.name);
            campocambiado.value=valor;
            //alert(campo_a_colorear.name);
            if (valor==2){
                campo_a_colorear.value="X";
            }else{
                campo_a_colorear.value="";
            }

        }

    </script>
</head>
<body onload="muestra_capa();">
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div class="container">
        <div class="titulo">
            <h2 style="color: #2496ca;">Tareas de alumnos</h2>
        </div>
        <br><br>
        <div style="float:left;">
            <a href="Envia_tareas_a_alumnos.php"><img border="0" src="../../../images/archivoaalumno.JPG" width="64" height="66"></a>
            <p>Enviar tareas a alumnos</p>
        </div>
        <div style="clear:both;">
            <h4 style="color:#2496ca">Tareas enviadas</h4>
        </div>
        <form name="formu" method="POST" action="borra_tareas.php" style=" background-color: #FFFFFF;padding:5px; margin-bottom:3%;">
        <?php
        //primero cargamos los nombres de las agrupaciones en una matiz indexada con los códigos
            $x=0;
            $result=mysqli_query($conexion, "SELECT cd_agrupacion, txt_agrupacion FROM agrupaciones_de_profesores where usuario_profesor= '$usuario_profesor'");
        //	echo "SELECT cd_agrupacion, txt_agrupacion FROM agrupaciones_de_profesores where usuario_profesor= '$usuario_profesor'<br>";
            while($row=mysqli_fetch_row($result)){
                $cd_agrupaciones[$x]=$row[0];
                $txt_agrupaciones[$x]=$row[1];
            //	echo "$txt_agrupaciones[$x] $cd_agrupaciones[$x] $x<br>";
                $x++;
            }
            $total_agrupaciones=$x;
        //		echo "---------------------------------------------------------------<br>";	
        //cargadas las agrupaciones, consultamos las actividades de tipo digial creadas para cada una de ellas 
            $i=0;
            for ($x=0;$x<$total_agrupaciones;$x++){
                $result=mysqli_query($conexion, "SELECT cd_actividad, txt_actividad FROM actividades where tipo=1 and cd_agrupacion=$cd_agrupaciones[$x]");
        //		echo "+ SELECT cd_actividad, txt_actividad FROM actividades where tipo=1 and cd_agrupacion=$cd_agrupaciones[$x]<br>";
                while($row=mysqli_fetch_row($result)){
                    $cd_agrup_actividad[$i]=$cd_agrupaciones[$x]; //en cada actividad encontrada repite el codigo de agrupacion
                    $txt_agrup_actividad[$i]=$txt_agrupaciones[$x];		
                    $cd_actividad[$i]=$row[0]; //mete cada codigo de actividad diferente 
                    $txt_actividad[$i]=$row[1];
        //			echo "$txt_agrup_actividad[$i] $cd_agrup_actividad[$i] i= $i<br>";			
        //			echo " - $txt_actividad[$i] $cd_actividad[$i] <br>";
                    $i++;
                }
            }
            $total_actividades=$i;
            //	echo "---------------------------------------------------------------<br>";	


        //cargadas las agrupaciones y sus actividades consultamos las tareas creadas para cada una de ellas con un distinct en el campo ruta. 
        //Es el único que garantiza que el archivo es el mismo, porque puede haber enviado el mismo archivo en distintas tandas 
        //según se incorporan alumnos o por los motivos que sea.
        //Traemos por tanto, las tareas enviadas a los alumnos

            $j=0;
            for ($x=0;$x<$total_actividades;$x++){
                $sSQL=" SELECT ruta, texto, curso, clase, fecha, cd_enlace, nombre, apellidos ";
                $sSQL=$sSQL." FROM enlaces_profesores,archivos_para_actividades, usuarios ";
                $sSQL=$sSQL." where enlaces_profesores.usuario= usuarios.usuario and ";
                $sSQL=$sSQL." archivos_para_actividades.usu_alumno= enlaces_profesores.usuario and ";
                //----------esta relación aveces da problemas, pues no se corresponden los usuario por no se qué problema en los insert
                //----------$sSQL=$sSQL." archivos_para_actividades.usu_alumno= enlaces_profesores.usuario and ";
                $sSQL=$sSQL." archivos_para_actividades.cd_agrupacion= enlaces_profesores.cd_agrupacion and ";
                $sSQL=$sSQL." archivos_para_actividades.cd_archivo= enlaces_profesores.cd_enlace and ";		
                $sSQL=$sSQL." archivos_para_actividades.cd_agrupacion= $cd_agrup_actividad[$x] and ";
                $sSQL=$sSQL." archivos_para_actividades.cd_actividad= $cd_actividad[$x] and ";	
                $sSQL=$sSQL." respuesta_al_archivo is null ";	
        //		echo "+ -- $sSQL<br> ";
                $result=mysqli_query( $conexion, $sSQL);
                while($row=mysqli_fetch_row($result)){
                    $cd_agrup_activ_tarea[$j]=$cd_agrup_actividad[$x];
                    $txt_agrup_activ_tarea[$j]=$txt_agrup_actividad[$x];		
                    $cd_actividad_tarea[$j]=$cd_actividad[$x];
                    $txt_actividad_tarea[$j]=$txt_actividad[$x];
                    $ruta[$j]=$row[0];
                    $texto[$j]=$row[1];
                    $curso[$j]=$row[2];
                    $clase[$j]=$row[3];
                    $fecha[$j]=$row[4];
                    $cd_enlace[$j]=$row[5];
                    $nombre[$j]=$row[6];
                    $apellidos[$j]=$row[7];
                    $j++;
                }
            }
            $total_tareas=$j;

        //ahora completamos el proceso recogiendo las respuestas también



            for ($x=0;$x<$total_tareas;$x++){
                $sSQL=" SELECT ruta, texto, fecha, cd_enlace ";
                $sSQL=$sSQL." FROM enlaces_ejercicios,archivos_para_actividades ";
                $sSQL=$sSQL." where archivos_para_actividades.usu_alumno= enlaces_ejercicios.usuario and ";
                $sSQL=$sSQL." archivos_para_actividades.cd_agrupacion= enlaces_ejercicios.cd_agrupacion and ";
                $sSQL=$sSQL." archivos_para_actividades.cd_archivo= enlaces_ejercicios.cd_enlace and ";		
                $sSQL=$sSQL." archivos_para_actividades.cd_agrupacion= $cd_agrup_activ_tarea[$x] and ";
                $sSQL=$sSQL." archivos_para_actividades.cd_actividad= $cd_actividad_tarea[$x] and ";	
                $sSQL=$sSQL." respuesta_al_archivo =$cd_enlace[$x] ";	
                //echo "+ -- $sSQL<br> ";
                $total_respuestas_para[$x]=0;
                $result=mysqli_query( $conexion, $sSQL);
                while($row=mysqli_fetch_row($result)){
                    $r=$total_respuestas_para[$x];
                    $ruta_resp[$x][$r]=$row[0];
                    $texto_resp[$x][$r]=$row[1];
                    $fecha_resp[$x][$r]=$row[2];
                    $enlace_resp[$x][$r]=$row[3];
                    $total_respuestas_para[$x]=$total_respuestas_para[$x]+1;
                }
            }

            $agru=0;
            $activ=0;
            $cuenta_capas=20;	
            $creando_capa=0;
            echo "<ul>";
            for ($x=0;$x<$total_tareas;$x++){
                if ($cd_agrup_activ_tarea[$x]!=$agru){
                    if ($creando_capa>0){
                        echo "</div>"; //cerramos la capa anteriormente abierta
                        $creando_capa=0;				
                    }
                    echo "</ul>";
                    echo "<ul>";			
                    echo "<h4>$txt_agrup_activ_tarea[$x]</h4><br>";
                    $agru=$cd_agrup_activ_tarea[$x];
                }
                if ($cd_actividad_tarea[$x]!=$activ){
                    if ($creando_capa>0){
                        echo "</div>"; //cerramos la capa anteriormente abierta
                        $creando_capa=0;
                    }
                    echo "$txt_actividad_tarea[$x]";
                    echo "<img  style=\"display:block\"name=\"desp$cuenta_capas\" border=\"0\" src=\"../../../images/desplegar.png\" width=\"30\" height=\"30\" onclick=\"oculta_desplegar(desp$cuenta_capas,recog$cuenta_capas, capa$cuenta_capas);\"><br>";
                    echo "<img  style=\"display:none\" name=\"recog$cuenta_capas\" border=\"0\" src=\"../../../images/recoger.png\" width=\"30\" height=\"30\" onclick=\"oculta_recoger(desp$cuenta_capas,recog$cuenta_capas, capa$cuenta_capas);\"><br>";			
                    $activ=$cd_actividad_tarea[$x];
                    //para no enviar todos los campos juntos, creamos un formulario por cada tarea, aunque al borrar solo podamos enviar uno a la vez
                    echo "<div style=\"display:none\" id=\"capa$cuenta_capas\">";
                    $creando_capa=1;
                    $cuenta_capas++;
                }

        //		echo "$cd_agrup_activ_tarea[$x] ";
        //		echo "$txt_agrup_activ_tarea[$x] ";		
        //		echo "$cd_actividad_tarea[$x] ";
        //		echo "$txt_actividad_tarea[$x] ";

                echo "<li class = 'tarea_mandada'><a href=\"$ruta[$x]\">$texto[$x]</a>";
                echo "<input type=\"hidden\" name=\"ruta$x\" value=\"$ruta[$x]\">";
                echo "<input type=\"hidden\" name=\"cd_enlace$x\" value=\"$cd_enlace[$x]\">";
                echo "<input type=\"hidden\" name=\"campo_modificado".$x."\" size=\"2\" value=\"0\">";
                echo "<input onclick=\"pasa_valor(formu.campo_modificado".$x.",formu.B4".$x.",2)\" type=\"button\" value=\"X\" name=\"B3".$x."\">";
                echo "<input  type=\"button\" value=\"\" name=\"B4".$x."\" onclick=\"pasa_valor(formu.campo_modificado".$x.",formu.B4".$x.",0)\"  style=\"color: #FF0000; font-weight: bold\"><br>";		
        //		echo "$curso[$x] ";
        //		echo "$clase[$x] ";
        //		echo "$fecha[$x] ";
        //		echo "$cd_enlace[$x] ";
                echo "$nombre[$x] ";
                echo "$apellidos[$x]";
                $rtotal=$total_respuestas_para[$x];
                for ($r=0;$r<$rtotal;$r++){
                    $rut=$ruta_resp[$x][$r];
                    $tex=$texto_resp[$x][$r];
                    $fech=$fecha_resp[$x][$r];
                    $enl=$enlace_resp[$x][$r];
                    echo "<li class ='tarea_recibida' ><a href=\"$rut\">$tex </a> ";
                    echo "<input type=\"hidden\" name=\"ruta".$x."_".$r."\" value=\"$rut\">";
                    echo "<input type=\"hidden\" name=\"cd_enlace".$x."_".$r."\" value=\"$enl\">";
                    echo "<input type=\"hidden\" name=\"campo_modificado".$x."_".$r."\" size=\"2\" value=\"0\">";
                    echo "<input onclick=\"pasa_valor(formu.campo_modificado".$x."_".$r.",formu.B4".$x."_".$r.",2)\" type=\"button\" value=\"X\" name=\"B3".$x."_".$r."\">";
                    echo "<input  type=\"button\" value=\"\" name=\"B4".$x."_".$r."\" onclick=\"pasa_valor(formu.campo_modificado".$x."_".$r.",formu.B4".$x."_".$r.",0)\"  style=\"color: #FF0000; font-weight: bold\"><br>";		
                    
                }
                echo "<input type=\"hidden\" name=\"total_recibidos_de_enviados$x\" value=\"$r\">";


            }


            echo "</ul></li>";
            echo "<input type=\"hidden\" name=\"total_enviados\" value=\"$x\"> ";	
            echo "<input type=\"button\" name=\"enviar\" value=\"Aplicar Borrar\" onclick =\"confirma_borrado();\">";
            mysqli_close($conexion);
        ?>
        </form>

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