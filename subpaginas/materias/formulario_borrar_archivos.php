<?php
    session_start(); 
    include ("../../base.php");
    //include ("utilities/seguridad.php");

    $txt_departamento=$_SESSION["txt_departamento"];
	$cd_departamento=$_SESSION["cd_departamento"];

	$seccion = $_POST["cd_cuelga_de"]; 
	$txt_seccion = $_POST["txt_cuelga_de"]; 
	if ($seccion==''){
		$seccion=0;
		$txt_seccion="Principal";
	}
    
    include("../../componentes/header.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar archivo : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/formulario_subida.css">

    <script language="JavaScript">
        function pasavalores(){
            formulario.cd_cuelga_de.value = formulario.cuelga_de.value ; 
            formulario.txt_cuelga_de.value = formulario.cuelga_de.options[formulario.cuelga_de.selectedIndex].text ; 	
            formulario.submit();
        }

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
    </script>
</head>

<body>
    
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div class="container" style="margin-bottom:50px">
        <div class="titulo">
            <h2 style="color: #2496ca;">Eliminar archivos del departamento: <?php echo " $txt_departamento"; ?></h2>
        </div><br>
        <form name="formulario2" method="POST" action="borra_archivos.php">
        <p style="color:#000080;"><b>Eliminación de ficheros subidos por profesores:</b></p>
        <p style="color:#800080; font-size:13px">Haga clic en los botones <b>X </b>para marcar los ficheros a borrar, y luego haga clic en Guardar.</p>
        <?php
            $contador=1;
            $numero_registros=0;
            //Si quiere ver la principal, mostramos los archivos subidos a la PRINCIPAL
            if ($seccion==0){
                $result=mysqli_query($conexion, "SELECT ruta, texto, cd_enlace FROM enlaces where  departamento=$cd_departamento and seccion=100 order by seccion,texto");
                echo "<Br><font size=\"3\">Principal</font><Br>";
                while($row=mysqli_fetch_row($result))
                {
                        echo "<input type=\"hidden\" name=\"cd$contador\" value = \"$row[0]\"><input type=\"text\" name=\"usuario$contador\" size=\"60\" value=\"$row[1]\" style=\"border: 2px solid #FFFFFF; background-color: #F1FEFE\"><input type=\"hidden\" name=\"ruta$contador\" size=\"60\" value=\"$row[0]\" style=\"border: 2px solid #FFFFFF; background-color: #F1FEFE\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[2]\">";
                        echo "<input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input  type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\" style=\"color: #FF0000; font-weight: bold\" >";
                        echo "<input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\"><br>";
                        $numero_registros=$numero_registros+1;
                        $contador=$contador+1;
                }
            }else{
                //si no,  mostramos todos los demás archivos.	
                $la_seccion="o";
                $result=mysqli_query($conexion, "SELECT ruta, texto, txt_seccion, cd_enlace FROM enlaces,configuracion_de_secciones where cd_seccion = $seccion and enlaces.seccion=configuracion_de_secciones.cd_seccion and departamento=$cd_departamento and cd_departamento =departamento order by texto");
            
                while($row=mysqli_fetch_row($result)){
            
                    echo "<input type=\"hidden\" name=\"cd$contador\" value = \"$row[0]\"><input type=\"text\" name=\"usuario$contador\" size=\"60\" value=\"$row[1]\" style=\"border: 2px solid #FFFFFF; background-color: #F1FEFE\"><input type=\"hidden\" name=\"ruta$contador\" size=\"60\" value=\"$row[0]\" style=\"border: 2px solid #FFFFFF; background-color: #F1FEFE\"><input type=\"hidden\" name=\"cd_enlace$contador\" size=\"5\" value=\"$row[3]\" >";
                    echo "<input type=\"hidden\" name=\"campo_modificado$contador\" value=\"0\"><input onclick=\"pasa_valor(campo_modificado$contador,B4$contador,2)\" type=\"button\" value=\"X\" name=\"B3\">";
                    echo "<input  type=\"button\" value=\"\" name=\"B4$contador\" onclick=\"pasa_valor(campo_modificado$contador,B4$contador,0)\"style=\"color: #FF0000; font-weight: bold\"><br>";
                    $numero_registros=$numero_registros+1;
                    $contador=$contador+1;
                }		
            }
            echo "<input type=\"submit\" value=\"Guardar\" name=\"enviar$contador\">";
            echo "</p><p>Número total de archivos almacenados: $numero_registros <input type=\"hidden\" name=\"numero_total_archivos\" value = \"$numero_registros\"size=\"8\"></p>";
        ?>
        </form>

        <p style="color:#000080;"><b>Listado actual de archivos para la sección: <?php echo $txt_seccion; ?></b></p>

        <form method="post" enctype="multipart/form-data" name="formulario" action="formulario_borrar_archivos.php">
        Seleccione la Sección para el filtrado: 
	
        <select size="1" name="cuelga_de" onchange ="pasavalores();">
        <option selected value="100" >Principal</option>
        <?php
        $cd_secciones[0]=0;
        $txt_secciones[0]=0;
        $profundidad[0]=0;	
        $contador=0;
        //Vamos a recuperar las secciones que cuelgan de la principal
        $result=mysqli_query($conexion, "SELECT cd_seccion, txt_seccion, profundidad, hija_de FROM configuracion_de_secciones where cd_departamento= $cd_departamento and cd_seccion>99 order by profundidad, cd_seccion, hija_de");
        while($row=mysqli_fetch_row($result)){
            $cd_secciones[$contador]=$row[0];
            $txt_secciones[$contador]=$row[1];
            $profundidad[$contador]=$row[2];		
            $hija_de[$contador]=$row[3];				
            $colocada[$contador]=0;						
            $contador++;
        }
        $cd_secciones[$contador]=99999;
        $txt_secciones[$contador]="ultima";
        $profundidad[$contador]=99;	
        $hija_de[$contador]=99999;					
        $colocada[$contador]=0;							
    
        for ($i=0;$i<$contador;$i++){
            $cd_secciones_actual=$cd_secciones[$i];
            $txt_secciones_actual=$txt_secciones[$i];
            $profundidad_actual=$profundidad[$i];		
            $hija_de_actual=$hija_de[$i];				
            $colocada_actual=$colocada[$i];						
            for ($j=0;$j<$contador;$j++){
                $cd_secciones_a_comparar=$cd_secciones[$j];
                $txt_secciones_a_comparar=$txt_secciones[$j];
                $profundidad_a_comparar=$profundidad[$j];		
                $hija_de_a_comparar=$hija_de[$j];				
                $colocada_a_comparar=$colocada[$j];										
                if ($colocada_a_comparar==0){
                    if ($hija_de_a_comparar==$cd_secciones_actual){
                        //avanzamos todos los registros de la matriz una posicion, entre la madre y la hija
                        //no podremos encontrar la hija antes que la madre, porque están ordenados por profundidad
                        $posicion=$j;	
                        for ($z=$i;$z<$j;$z++){		
    
                            $cd_secciones_temp=$cd_secciones[$posicion-1];
                            $txt_secciones_temp=$txt_secciones[$posicion-1];
                            $profundidad_temp=$profundidad[$posicion-1];		
                            $hija_de_temp=$hija_de[$posicion-1];				
                            $colocada_temp=$colocada[$posicion-1];						
                        
                            $cd_secciones[$posicion]=$cd_secciones_temp;
                            $txt_secciones[$posicion]=$txt_secciones_temp;
    //						$txt_secciones[$posicion]="movida";
                            $profundidad[$posicion]=$profundidad_temp;		
                            $hija_de[$posicion]=$hija_de_temp;				
                            $colocada[$posicion]=$colocada_temp;						
                            $posicion=$posicion-1;
                        }
                        //ahora metemos a la hija en la posicion siguiente a la de la madre
                        $cd_secciones[$i+1]=$cd_secciones_a_comparar;
                        $txt_secciones[$i+1]=$txt_secciones_a_comparar;
                        $profundidad[$i+1]=$profundidad_a_comparar;		
                        $hija_de[$i+1]=$hija_de_a_comparar;				
                        $colocada[$i+1]=1;						
                    }			
                }
            }
        }
        
        for ($i=0;$i<$contador;$i++){
            $espaciado="";
            for ($j=1;$j<$profundidad[$i];$j++){
                $espaciado=$espaciado."--";
            }
            $color=dechex(2370140 +($profundidad[$i] * 1111));
            echo "<font color=\"#$color\" >";
            echo "<option value=\"$cd_secciones[$i]\">$espaciado$txt_secciones[$i]</option>";
    
        }
        mysqli_close($conexion); 
        ?>
            </select><input type="hidden" name="cd_cuelga_de" size="4" value="<?php echo $seccion; ?> " >
            <input type="hidden" name="txt_cuelga_de" value="<?php echo $txt_seccion; ?> ">
        </form>
    </div>    

    <?php
        include("../../componentes/footer.php");
    ?> 
</body>
