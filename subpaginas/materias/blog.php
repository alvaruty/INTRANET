<?php 
session_start(); 

include("../../base.php");
include("../../componentes/header.php"); 
//--------Coge las variables de materias.php-------------
$cd_departamento = $_SESSION["cd_departamento"];
$txt_departamento = $_SESSION["txt_departamento"];
//var_dump($cd_departamento);
//---------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog : IES Las Salinas</title>
    <link rel="icon" href="../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/blog.css">
</head>
<body>
<script type="text/javascript" language="JavaScript1.2">

//Esta funcion pasa los valores de los optionbutton a los campos ocultos, para luego pasar ?tos a la p?ina gen?ica de asignaturas
//sabiendo as?la asignatura que debe mostrar.
function pasavalores(numero, texto){
	//window.parent.frames[
	//window.parent.frames['encabezado2'].
	formulario.cd_blog.value=numero;
	formulario.txt_blog.value=texto;
	formulario.B2.disabled=false;
}
//-->
</script>
<section class="formulario">
    <div class="img">
        <a href="../materias.php"><img src="../../imagenes/hacia-atras.png" width="30" height=auto></a>
    </div>
    <div class="formulario-container">
        <h2>Aula virtual: <?php echo "$txt_departamento" ?></h2>
        <div class="formularios">
            <form method="POST" action="blogs.php" name= "formulario" target="_blank">
                <input type="radio" value="23"  name="blogs" onclick="pasavalores(23,'FILOSOFESO');">FILOSOFESO (Todos los alumnos) <br>
                <input type="hidden" name="cd_blog" value="0" size="4">
                <input type="hidden" name="txt_blog" value="0" size="25">
                <br>
                <p><input class="boton-entrar" type="submit" value="Entrar" name="B2" ></p>
            </form>
        </div>
    </div>
</section>

</body>
<?php include("../../componentes/footer.php"); ?>