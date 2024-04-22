<?php
    session_start(); 
    include ("../../base.php");
    include ("utilities/seguridad.php");
    
    $txt_departamento = $_SESSION["txt_departamento"];
    $cd_departamento = $_SESSION["cd_departamento"];
    
    include("../../componentes/header.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colgar archivo : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/formulario_subida.css">
</head>

<body>
    
    <div class="img">
            <a href="../materias/profesores.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    
    <div class="container">
        <div class="titulo">
            <h2 style="color: #2496ca;">Formulario para colgar archivos en el departamento <?php echo " $txt_departamento"; ?></h2>
        </div>
        <form action="subearchivo.php" method="POST" enctype="multipart/form-data"> 
        <br>
        <br>
        
        <div class="ayuda">
            <p>Desde aquí los profesores pueden subir los archivos que consideren y dejarlos colgados en la zona pública, a disposición de los alumnos. Para subir un archivo al servidor, debe tenerlo guardado en algún sistema de almacenamiento (disco duro, CD, DVD, pendrive, etc.). Ahora siga los siguientes pasos para que el archivo sea subido correctamente. <b>Los nombres de archivo</b> no deben contener caracteres extraños, tales como ., º, /, etc. Es más seguro que solo tengan letras, números y espacios. Tampoco deben exceder el tamaño máximo, que actualmente está fijado en 80 MB.</p>
        </div>
        <br>        
        <div style="border: 3px solid rgb(191, 216, 242); padding: 10px; margin-bottom: 5%; color: black; ">
            <p>Introduzca a continuación el texto que aparecerá como enlace al archivo que va a subir:</p> 
                <input type="text" name="cadenatexto" size="95" maxlength="95">
            <p>
            <br> 
            <p>Seccione la sección del departamento en la que colocar el enlace al archivo a subir:  </p>
            <select size="1" name="desplegable_secciones">
                <option value="100">Página Principal</option>
                
                <?php
                    //Vamos a añadir las demás secciones además de las fijas (Principal y recursos)
                    $txt_departamento = $_SESSION["txt_departamento"];
                    $cd_departamento = $_SESSION["cd_departamento"];

                    $cd_secciones[0] = 0;
                    $txt_secciones[0] = 0;
                    $profundidad[0] = 0;	
                    $contador = 0;
                    
                    //Vamos a recuperar las secciones que cuelgan de la principal
                    $result = mysqli_query($conexion, "SELECT cd_seccion, txt_seccion, profundidad, hija_de FROM configuracion_de_secciones where cd_departamento= $cd_departamento and cd_seccion>99 order by profundidad, cd_seccion, hija_de");
                    
                    while($row = mysqli_fetch_row($result)){
                        $cd_secciones[$contador] = $row[0];
                        $txt_secciones[$contador] = $row[1];
                        $profundidad[$contador] = $row[2];		
                        $hija_de[$contador] = $row[3];				
                        $colocada[$contador] = 0;						
                        $contador++;
                    }
                    
                    $cd_secciones[$contador] = 99999;
                    $txt_secciones[$contador] = "ultima";
                    $profundidad[$contador] = 99;	
                    $hija_de[$contador] = 99999;					
                    $colocada[$contador] = 0;							

                    for ($i = 0; $i < $contador; $i++){
                        $cd_secciones_actual = $cd_secciones[$i];
                        $txt_secciones_actual = $txt_secciones[$i];
                        $profundidad_actual = $profundidad[$i];		
                        $hija_de_actual = $hija_de[$i];				
                        $colocada_actual = $colocada[$i];						
                        
                        for ($j = 0; $j < $contador; $j++){
                            $cd_secciones_a_comparar = $cd_secciones[$j];
                            $txt_secciones_a_comparar = $txt_secciones[$j];
                            $profundidad_a_comparar = $profundidad[$j];		
                            $hija_de_a_comparar = $hija_de[$j];				
                            $colocada_a_comparar = $colocada[$j];										
                            
                            if ($colocada_a_comparar == 0){
                                if ($hija_de_a_comparar == $cd_secciones_actual){
                                    //avanzamos todos los registros de la matriz una posicion, entre la madre y la hija
                                    //no podremos encontrar la hija antes que la madre, porque están ordenados por profundidad
                                    $posicion = $j;	
                                    
                                    for ($z = $i; $z < $j; $z++){		
                                        $cd_secciones_temp = $cd_secciones[$posicion - 1];
                                        $txt_secciones_temp = $txt_secciones[$posicion - 1];
                                        $profundidad_temp = $profundidad[$posicion - 1];		
                                        $hija_de_temp = $hija_de[$posicion - 1];				
                                        $colocada_temp = $colocada[$posicion - 1];						
                                        
                                        $cd_secciones[$posicion] = $cd_secciones_temp;
                                        $txt_secciones[$posicion] = $txt_secciones_temp;
                                        $profundidad[$posicion] = $profundidad_temp;		
                                        $hija_de[$posicion] = $hija_de_temp;				
                                        $colocada[$posicion] = $colocada_temp;						
                                        $posicion = $posicion - 1;
                                    }
                                    
                                    //ahora metemos a la hija en la posicion siguiente a la de la madre
                                    $cd_secciones[$i + 1] = $cd_secciones_a_comparar;
                                    $txt_secciones[$i + 1] = $txt_secciones_a_comparar;
                                    $profundidad[$i + 1] = $profundidad_a_comparar;		
                                    $hija_de[$i + 1] = $hija_de_a_comparar;				
                                    $colocada[$i + 1] = 1;						
                                }			
                            }
                        }
                    }

                    for ($i = 0; $i < $contador; $i++){
                        $espaciado = "-";
                        
                        for ($j = 1; $j < $profundidad[$i]; $j++){
                            $espaciado = $espaciado . "-";
                        }
                        
                        echo "<option value=\"$cd_secciones[$i]\">$espaciado$txt_secciones[$i]</option>";
                    }

                    mysqli_close($conexion); 
                ?>
                
            </select>
            <br> 
            <br> 
            <p>Seleccione el archivo que quieres subir.</p>
            <input name="userfile" type="file">
            <br><br>
            <input type="submit" value="Subir el archivo">
        </div>
    </form> 
    </div>    

    <?php
        include("../../componentes/footer.php");
    ?> 
</body>
