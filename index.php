<?php 
session_start();
include("base.php"); 

// enlaces inicio
$sSQL = "SELECT `texto`, `vinculo`, `cd_aviso`, `departamento`, `fecha` FROM `avisos_profes` WHERE `departamento` = '-1' ORDER BY `fecha` DESC";
$result = mysqli_query($conexion, $sSQL);

$contador2 = 0;
while($row = mysqli_fetch_assoc($result)){
    $texto[$contador2] = $row['texto'];
    $vinculo[$contador2] = $row['vinculo'];
    $cd_aviso[$contador2] = $row['cd_aviso'];
    $departamento_aviso[$contador2] = $row['departamento'];
    $fecha[$contador2] = $row['fecha'];
    $contador2++;
}

//var_dump($contador2);

//$cd_departamento = $_POST["cd_asignatura_seleccionada"];
//$txt_departamento = $_POST["txt_asignatura_seleccionada"];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IES Las Salinas</title>
    <link rel="icon" href="imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const bgImages = ['/imagenes/banner.jpg', '/imagenes/banner2.jpg', '/imagenes/banner3.jpg'];
        let currentIndex = 0;

        const bgImage = document.querySelector('.bg-image');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');

        function changeBackground() {
            bgImage.style.backgroundImage = `url(${bgImages[currentIndex]})`;
        }

        prevBtn.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + bgImages.length) % bgImages.length;
            changeBackground();
        });

        nextBtn.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % bgImages.length;
            changeBackground();
        });

        // Carga la primera imagen de fondo
        changeBackground();
    });

    </script>

</head>

<body>
    <script type="text/javascript" language="JavaScript1.2">
        function pasa_codigo_asignatura(cod, text){
            formulario.cd_asignatura_seleccionada.value = cod;
            formulario.txt_asignatura_seleccionada.value = text;
        }

    </script>

    <?php include("componentes/header.php"); ?>

    <section class="seccion1">
        <div class="bg-image"></div>
        <div class="container">
            <h1>Bienvenido a I.E.S Las Salinas</h1>
            <p>Descubre nuestro compromiso con la excelencia académica y el desarrollo integral de nuestros estudiantes.</p>
            <div class="arrow-buttons">
                <button class="prev-btn">&#8249;</button>
                <button class="next-btn">&#8250;</button>
            </div>
        </div>
    </section>

    <section class="botones">
        <a href="#">Programaciones didácticas</a>
        <a href="#">Recuperación de pendientes</a>
        <a href="#">Criterios de evaluación</a>
    </section>

    <section class="info-destacada">
        <h2>Información destacada</h2>
        <ul class="lista-destacada">
            <li>
                <img src="imagenes/infoAdmision.png" alt="información 1" />
                <div class="info1">
                    <h3>Admisión del curso 24-25</h3>
                    <button onclick=""><a href="http://www.ieslassalinas.org/Privada/Avisos/uploads/08-02-2024%2002-20-53admision24_25.pdf5.pdf">Más información</a></button>
                </div>
            </li>
            <li>
                <img src="#" alt="información 1" />
                <div class="info1">
                    <h3>Titulo</h3>
                    <p></p>
                    <button><a href="#">Más información</a></button>
                </div>
            </li>
            <li>
                <img src="#" alt="información 1" />
                <div class="info1">
                    <h3>Titulo</h3>
                    <button><a href="#">Más información</a></button>
                </div>
            </li>
        </ul>

    </section>

    <section class="imagenes">
        <img src="imagenes/twitterpes.jpg" class="centrar-imagen">
    </section>

    <section class="enlaces">
        <div class="columns-title">
            <h3>Últimos avisos</h3>
        </div>
        <div class="columns-container">
            <div class="column-enlace">
                <?php
                $mitad2 = ceil($contador2 / 2); // Obtener la mitad de los registros
                for ($i = 0; $i < $mitad2; $i++) {
                    if (!empty($vinculo[$i])) {
                        echo "<a href=\"$vinculo[$i]\" target=\"_blank\">$texto[$i] - ($fecha[$i])</a><br>";
                    } else {
                        echo "<a>$texto[$i] - ($fecha[$i])</a><br>";
                    }
                    }
                ?>
            </div>
            <div class="column-enlace">
                <?php
                for($i = $mitad2; $i < $contador2; $i++){
                if (!empty($vinculo[$i])) {
                    echo "<a href=\"$vinculo[$i]\" target=\"_blank\">$texto[$i] - ($fecha[$i])</a><br>";
                } else {
                    echo "<a>$texto[$i] - ($fecha[$i])</a><br>";
                }
                }
                ?>
            </div>
        </div>
    </section>
    

    <!-- Más secciones aquí -->

<?php include("componentes/footer.php"); ?>

</body>

</html>
