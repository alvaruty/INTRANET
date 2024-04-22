<?php 
//include("../base.php"); 
//session_start();


// departamentos
$sSQL = "SELECT `cd_departamento`, `departamento` FROM `departamentos` WHERE `cd_departamento` NOT IN (30, 31, 32)";
$result = mysqli_query($conexion, $sSQL);
$count = 0;

while($row = mysqli_fetch_row($result)){
    $cd_departament[$count] = $row[0];
    $departamento[$count] = $row[1];
    $count++;
}
?>
    <script type="text/javascript" language="JavaScript1.2">
        function pasa_codigo_asignatura(cod, text){
            formulario.cd_asignatura_seleccionada.value = cod;
            formulario.txt_asignatura_seleccionada.value = text;
        }
    </script>
    <style>
        /* Reset de estilos */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #e0f3ff; 
    color: #333;
}

.container {
    width: 90%;
    margin: 0 auto;
}

/* Estilos de la barra de navegación */
header {
    background-color: #ffffff; 
    color: #2496ca;
    padding: 20px;
    display: flex;
    align-items: center; 
    
}

.logo img {
    width: 120px;
    margin-right: auto; 
}

nav ul {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

nav ul li {
    margin-left: 20px;
}

nav ul li:first-child {
    margin-left: 0;
}

nav ul li a {
    color: #2496ca;
    text-decoration: none;
}

nav ul li a:hover {
    text-decoration: underline;
}

/* Inicio sesion */
.login-form {
    margin-left: auto;
    display: flex;
    align-items: center;
}

.login-form form {
    display: flex;
    align-items: center;
}

.login-form input[type="text"],
.login-form input[type="password"],
.login-form button {
    margin-left: 10px;
    padding: 5px 10px;
}

.login-form input[type="text"],
.login-form input[type="password"] {
    width: 120px;
}

.login-form button {
    background-color: #2496ca;
    color: #fff;
    border: none;
    cursor: pointer;
}

.login-form button:hover {
    background-color: #555;
}

/* Estilos del menú desplegable */
.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #fff; 
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); 
    z-index: 1;
    width: 500px; 
    max-height: 300px; 
    overflow-y: auto; 
}

.columna {
    float: left;
    width: 50%;
}

.dropdown-content a {
    color: #2496ca; 
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s ease; 
}

.dropdown-content a:hover {
    background-color: #f2f2f2; 
}

.dropdown:hover .dropdown-content {
    display: block;
}
    </style>
    <header>
        <script language="JavaScript">
            function pregunta_cierra_sesion(){
            if(confirm("¿Está seguro de que quiere CERRAR su sesión?")){
                    //header("Location: cierra_sesion.php");

                    cierra_sesion.submit();
                }
            }
            function envia_comprobando_contrasena(){
            //Comprobamos que la onctraseña y el usuario son iguales. Si lo son avisamos de que deben cambiar la contraseña
                    if (formulario2.usuario.value==formulario2.contrasena.value){
                        alert("Si su contraseña es igual a su usuario, por seguridad debe cambiarla desde el menú USUARIOS");
                    }
                    formulario2.submit();
            
            }
        </script>
        <!-- Formulario materias -->
        <form method="post" action="/subpaginas/materias.php" name="formulario"><!-- cambiar ruta a: subpaginas/materias.php -->
            <p><input type="hidden" name="cd_asignatura_seleccionada" size="10"></p>
            <p><input type="hidden" name="txt_asignatura_seleccionada" size="10"></p>
        </form>

        <div class="logo">
            <img src="../../../imagenes/logo-sinFondo.png" alt="Logo" />
        </div>
        <nav>
            <ul>
                <li><a href="../../../index.php">Principal</a></li>
                <li><a href="/subpaginas/novedades.php">Novedades</a></li>
                <li><a href="/subpaginas/jefatura.php">Jefatura</a></li>
                <li><a href="/subpaginas/secretaria.php">Secretaría</a></li>
                <li><a href="/subpaginas/biblioteca.php">Biblioteca</a></li>
                <li class="dropdown">
                    <a>Materias</a> <!-- luego borrar esta referencia -->
                    <div class="dropdown-content">
                        <div class="columna">
                            <?php
                            $mitad = ceil($count / 2); // Obtener la mitad de los registros

                            for($i = 0; $i < $mitad; $i++){
                                echo "<a href='#' onmouseover='pasa_codigo_asignatura(\"{$cd_departament[$i]}\", \"{$departamento[$i]}\");' onclick=\"formulario.submit();\">$departamento[$i]</a>";
                            }
                            ?>
                        </div>
                        <div class="columna">
                            <?php
                            for($i = $mitad; $i < $count; $i++){
                                echo "<a href='#' onmouseover='pasa_codigo_asignatura(\"{$cd_departament[$i]}\", \"{$departamento[$i]}\");' onclick=\"formulario.submit();\">$departamento[$i]</a>";
                            }
                            ?>
                        </div>
                    </div>
                </li>

                <li><a href="/subpaginas/ampa.php">AMPA</a></li>
                <li><a href="/subpaginas/usuarios.php">Usuarios</a></li>
                <li><a href="/subpaginas/examenes.php">Exámenes</a></li>
                <?php
                    if (empty($_SESSION["usuario"])){}else{
                        echo "<li><a href=\"/subpaginas/cEscolar.php\">C.Escolar</a></li>";
                    }
                ?>
                <!-- <li><a href="/subpaginas/cEscolar.php">C.Escolar</a></li>  Esto solo aparece segun el usuario--> 
            </ul>
        </nav>

        <!-- Formulario de inicio de sesión -->
        <div class="login-form">
            <form action="../../componentes/control_izquierda.php" method="POST" target="_self" name="formulario2">
            <?php 
                if (empty($_GET["errorusuario"])){
                    $error = "no"; // Esto es porque no han intentado validarse siquiera, es decir, es un visitante anónimo
                } else {
                    $error = $_GET["errorusuario"];
                }
                
                if ($error == "si"){ 
                    echo '<div class="input-group">';
                    echo '<div style="display: inline-block;">'; 
                    echo '<span style="color:800000"><b>Datos incorrectos<br>No está registrado</b></span>';
                    echo '<input type="text" name="usuario" placeholder="Usuario">';
                    echo '<input type="password" name="contrasena" placeholder="Contraseña">';
                    echo '</div>';
                    echo '<button type="button" style="margin-left: 10px;" onclick="envia_comprobando_contrasena();">Enviar</button>';
                    echo '</div>';
                } else {
                    if (empty($_SESSION["usuario"])) {
                        echo '<div class="input-group">';
                        echo '<input type="text" name="usuario" placeholder="Usuario">';
                        echo '<input type="password" name="contrasena" placeholder="Contraseña">';
                        echo '</div>';
                        echo '<button type="button" onclick="envia_comprobando_contrasena();">Enviar</button>';
                    } else {            
                        if ($_SESSION["autentificado"] != "SI") { 
                            echo '<div class="input-group">';
                            echo '<input type="text" name="usuario" placeholder="Usuario">';
                            echo '<input type="password" name="contrasena" placeholder="Contraseña">';
                            echo '</div>';
                            echo '<button type="button" onclick="envia_comprobando_contrasena();">Enviar</button>';
                        } else {
                            $nombre = $_SESSION["nombre_u"];
                            $apellidos = $_SESSION["apellido_u"];
                            echo "<p align=\"left\">$nombre $apellidos: ";
                            echo '<button type="button" onclick="pregunta_cierra_sesion();">Cerrar sesión</button></p>';
                        }
                    }
                } 
            ?>
        </div>
        <div class="notificaciones">
            <?php

        
            //Primero consultamos si tiene alguna tarea reciente asignada.

            //<br>
            //<img border="0" src="images/archivonuevorecibido.PNG" width="53" height="56"><br>
            //<img border="0" src="images/mensajenuevorecibido.png" width="50" height="50"><p>&nbsp;</div>

            //Luego consultamos si ha recibido algún trabajo de los profesores
            //Luego consultamos si se le ha incluido en algún blog recientemente

            if (empty($_SESSION["usuario"])){
            $error="si";
            }else{


            $usu=$_SESSION["usuario"];
            $ultimo_acce=$_SESSION["ultimo_acceso"];


            $hoy=date("Y-m-d");
            $sSQL="SELECT cd_departamento FROM blogs, alumnos_agrupados where  activo=1 and fecha_creacion>'$ultimo_acce' and blogs.cd_agrupacion= alumnos_agrupados.cd_agrupacion and alumnos_agrupados.usuario='$usu'";
            //echo $sSQL;
            $encontrados=0;
            $result=mysqli_query( $conexion, $sSQL);
            while($row=mysqli_fetch_row($result)){
                $encontrados++;
            }
            if ($encontrados>0){

                echo "<img border=\"0\" tittle=\"Hay nuevos Blogs que requieren tu atención en Materias\" src=\"images/incluidoennuevoBlog.png\" width=\"52\" height=\"38\">";

            }
            }

            //mysqli_close($conexion);
            ?>
        </div>
        </form>
            <form action="../../componentes/cierra_sesion.php" method="POST" target="_self" name="cierra_sesion"> 
        </form>

    </header>