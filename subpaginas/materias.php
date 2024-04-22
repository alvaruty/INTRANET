<?php 
	session_start(); 
	include ("../base.php");
	//include ("../../../cadenas.php");
    //Comprobar si las variables de sesión están asignadas
    $cd_departamento = isset($_POST["cd_asignatura_seleccionada"]) ? $_POST["cd_asignatura_seleccionada"] : (isset($_SESSION["cd_departamento"]) ? $_SESSION["cd_departamento"] : 'Valor por defecto');
    $txt_departamento = isset($_POST["txt_asignatura_seleccionada"]) ? $_POST["txt_asignatura_seleccionada"] : (isset($_SESSION["txt_departamento"]) ? $_SESSION["txt_departamento"] : 'Seleccione una asignatura');
    //almacena las variables
    if(isset($_POST["cd_asignatura_seleccionada"]) && isset($_POST["txt_asignatura_seleccionada"])) {
        $_SESSION["cd_departamento"] = $cd_departamento;
        $_SESSION["txt_departamento"] = $txt_departamento;
    }
	
	if (empty($_SESSION["usuario"])){
		$usu='00000';
		$permisos=-1;//sin registrar
	}else{
		$usu=$_SESSION["usuario"];//Puede que no esté registrado, pero lo cargamos para que se vean los libros
		$permisos=$_SESSION["permisos"];
	}
	
	if ($permisos==0){
		$usuario_alumno=quitaPdePadre($usu);	
	}else{
		$usuario_alumno=$usu;	
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/materias.css">

    <script language="JavaScript">
        var color_fondo_celda;
        function pasavalores(cod_seccion){
            //window.parent.frames[
            //window.parent.frames['encabezado2'].
            
            form_envia_subrecursos.cd_seccion_seleccionada.value=cod_seccion;
            form_envia_subrecursos.submit(); 
        }
        
        function activaCelda(src) {
            with(document) {
                var cel = getElementById(src);
                with(cel) {
                    color_fondo_celda=style.backgroundColor;
                    style.backgroundColor="#CCFFFF";
                    style.cursor='hand';
                }
            }
            form_envia_subrecursos.cd_seccion_seleccionada.value=src;
        }

        function desactivaCelda(src) {
            with(document) {
                var cel = getElementById(src);
                with(cel) {
                    style.backgroundColor=color_fondo_celda;
                    style.cursor='default';
                }
            }
        }
    </script>
</head>
<body>
<script type="text/javascript" language="JavaScript1.2">
        function pasa_codigo_asignatura(cod, text){
            formulario.cd_asignatura_seleccionada.value = cod;
            formulario.txt_asignatura_seleccionada.value = text;
        }
    </script>

<?php 
include("../componentes/header.php"); 
?>

    <section class="materia">
        <h2><?php echo "$txt_departamento" ?></h2>
        <div class="invisible-table">
            <div class="image-row">
                <div class="image-item">
                    <a href="materias/blog.php"><img src="../imagenes/blogging.png" alt="imagen 1" style="width: 100px;"></a>
                    <a href="materias/blog.php" style="font-size: 14px;">Aula virtual</a>
                </div>
                <div class="image-item">
                    <a href="materias/rendimiento.php"><img src="../imagenes/rendimiento.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="materias/rendimiento.php" style="font-size: 14px;">Mi rendimiento</a>
                </div>
                <div class="image-item">
                    <a href="materias/enviarArchivo.php"><img src="../imagenes/enviar.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="materias/enviarArchivo.php" style="font-size: 14px;">Enviar archivo</a>
                </div>
                <div class="image-item">
                    <a href="materias/misArchivos.php"><img src="../imagenes/carpeta.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="materias/misArchivos.php" style="font-size: 14px;">Mis archivos</a>
                </div>
                <div class="image-item">
                    <a href="materias/actuaciones.php"><img src="../imagenes/lapiz.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="materias/actuaciones.php" style="font-size: 14px;">Actuaciones particulares</a>
                </div>
                <div class="image-item">
                    <a href="materias/comunicaciones.php"><img src="../imagenes/grupo.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="materias/comunicaciones.php" style="font-size: 14px;">Comunicaciones</a>
                </div>
                <div class="image-item">
                    <a href="materias/faltas.php"><img src="../imagenes/ausencia.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="materias/faltas.php" style="font-size: 14px;">Faltas</a>
                </div>
                <div class="image-item">
                    <a href="materias/profesores.php"><img src="../imagenes/mentor.png" alt="imagen 2" style="width: 100px;"></a>
                    <a href="materias/profesores.php" style="font-size: 14px;">Profesores</a>
                </div>
            </div>

            <!-- Secciones del Departamento -->
            <?php
            $contador=1;
            $result=mysqli_query($conexion, "SELECT texto, vinculo FROM avisos_profes where departamento= $cd_departamento and seccion=100 ");
            while($row=mysqli_fetch_row($result)){
                if ($row[1]==''){
                    echo " $row[0] ";    
                }else{
                    echo " <a href='$row[1]' target='_blank'>$row[0].<a>";            
                }   
                    echo "<br>";
                }   
            ?>
            
            <form method="POST" action="Recursos/subrecursos.php" name="form_envia_subrecursos">
                <input type="hidden" name="prof_seccion_seleccionada" size="5" value="0" style="font-weight: 700">

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
                                $posicion=$j;    
                                for ($z=$i;$z<$j;$z++){        
                                    $cd_secciones_temp=$cd_secciones[$posicion-1];
                                    $txt_secciones_temp=$txt_secciones[$posicion-1];
                                    $profundidad_temp=$profundidad[$posicion-1];        
                                    $hija_de_temp=$hija_de[$posicion-1];                
                                    $colocada_temp=$colocada[$posicion-1];                        
                                
                                    $cd_secciones[$posicion]=$cd_secciones_temp;
                                    $txt_secciones[$posicion]=$txt_secciones_temp;
                                    $profundidad[$posicion]=$profundidad_temp;        
                                    $hija_de[$posicion]=$hija_de_temp;                
                                    $colocada[$posicion]=$colocada_temp;                        
                                    $posicion=$posicion-1;
                                }
                                $cd_secciones[$i+1]=$cd_secciones_a_comparar;
                                $txt_secciones[$i+1]=$txt_secciones_a_comparar;
                                $profundidad[$i+1]=$profundidad_a_comparar;        
                                $hija_de[$i+1]=$hija_de_a_comparar;                
                                $colocada[$i+1]=1;                        
                            }            
                        }
                    }
                }
                ?> 

                <div class="columns-container">
                    <div class="column">
                        <h3>Secciones del Departamento</h3>
                        <ul>
                        <?php
                            for ($i=0;$i<$contador;$i++){
                                $color='FFFFFF';
                                if ($profundidad[$i]==1){
                                    $color=dechex(15395583);
                                }
                                echo "<li style=\"background-color: #$color\"; id=$i onmouseover=activaCelda('$i'); onmouseout=desactivaCelda('$i');  onclick=\"pasavalores($cd_secciones[$i]);\">";
                                for ($j=0;$j<$profundidad[$i];$j++){
                                    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                }        
                                echo "$txt_secciones[$i]</li>";
                            }
                        ?>
                        </ul>
                    </div>
                    <input type="hidden" name="cd_seccion_seleccionada">
                    <input type="hidden" value="100" name="origen" size="20"></p>
                </form>

                <?php
                $contador=0;
                if ($usu!=''){
                    $result=mysqli_query($conexion, "SELECT ruta_icono, ruta_libro from libros_usuarios where usuario='$usuario_alumno' and cd_departamento=$cd_departamento and activo=1");
                    while($row=mysqli_fetch_row($result)){
                        $ruta_foto[$contador]=$row[0];
                        $ruta_libro[$contador]=$row[1];        
                        $contador++;
                    }
                    if ($contador>0){
                        echo "Tus libros de texto:<br>";
                    }    
                    for ($i=0;$i<$contador;$i++){
                        echo "<a target=\"_blank\" href=\"libros/$ruta_libro[$i]\"><img class=conborde border=\"0\" src=\"libros/$ruta_foto[$i]\" width=\"128\" height=\"128\"></a><br>";
                    }    
                }else{
                    echo "Para ver tus libros de texto, debes introducir tu usuario y contraseña.";
                }
                ?>  

                <div class="column-enlace">
                    <h3>Archivos</h3>
                    <?php
                    $contador=1;
                    $result=mysqli_query($conexion, "SELECT ruta, texto, seccion FROM enlaces where departamento= $cd_departamento and seccion=100");
                    while($row=mysqli_fetch_row($result)){
                        echo "<a href=\"$row[0]\" target=\"_blank\"> $row[1]</a><br>";    
                    }
                    mysqli_close($conexion);
                    ?>
                </div>
            </div>
        </div>
    </section>

<?php include("../componentes/footer.php"); ?>
</body>
</html>
