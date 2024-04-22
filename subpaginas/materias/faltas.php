<?php 
error_reporting(0);
session_start();
include("../../base.php");
include("../../componentes/header.php"); 
include ("utilities/seguridad_alumnos.php");


$cd_departamento = $_SESSION["cd_departamento"];
$txt_departamento = $_SESSION["txt_departamento"];

	//Para mostrar los datos del hijo, comprobamos primero si es padre, y luego le quitamos la letra "p" que debe llevar al principio
	$usuario_t=$_SESSION["usuario"];
	$permisos=$_SESSION["permisos"];
	
	if ($permisos==0){
		$usuario_alumno=quitaPdePadre($usuario_t);	

	}else{
		$usuario_alumno=$usuario_t;	
	}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faltas : IES Las Salinas</title>
    <link rel="icon" href="/../imagenes/logo-sinFondo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/faltas.css">
</head>
<body>

    <div class="img">
            <a href="../materias.php"><img src="../../imagenes/hacia-atras.png" width="30" height="auto"></a>
    </div>
    <div class="container">
    <div class="titulo">
        <h2 style="color: #2496ca;">Faltas de asistencia</h2>
    </div>
    <br>
    <p>Control de faltas registradas (I: Injustificada, J: Justificada, R: Retraso): </p>
    <form method="POST" action="consultar_faltas.php" webbot-action="--WEBBOT-SELF--">
        <input type="hidden" name="VTI-GROUP" value="0">
    </form>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Día</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $por_pagina = 20; // número de filas por página
                $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                $inicio = ($pagina_actual - 1) * $por_pagina;

                $result = mysqli_query($conexion, "SELECT fecha_lunes, dia_semana, hora_dia, tipo_falta FROM faltas WHERE usuario_alumno='$usuario_alumno' ORDER BY fecha_lunes DESC, dia_semana DESC, hora_dia LIMIT $inicio, $por_pagina");

                while($row = mysqli_fetch_row($result)) {
                    $incremento = 86400 * $row[1];
                    $fecha_cambiada = strtotime($row[0]) + $incremento;
                    $fecha_cambiada_date = date("d/m/Y", $fecha_cambiada);   
                    echo "<tr>";
                    echo "<td>$fecha_cambiada_date</td>";
                    echo "<td>" . ucfirst(diaSemana($row[1])) . "</td>";
                    echo "<td>$row[3]</td>";
                    echo "</tr>";
                }
                // No cierres la conexión aquí

                function diaSemana($num) {
                    switch($num) {
                        case 0: return 'lunes';
                        case 1: return 'martes';
                        case 2: return 'miércoles';
                        case 3: return 'jueves';
                        case 4: return 'viernes';
                        default: return '';
                    }
                }
            ?>  
        </tbody>
    </table>
    <br>
    <div class="pagination">
    <?php 
    // Botones de paginación
    $result_count = mysqli_query($conexion, "SELECT COUNT(*) as total FROM faltas WHERE usuario_alumno='$usuario_alumno'");
    $row_count = mysqli_fetch_assoc($result_count);
    $total_filas = $row_count['total'];
    $total_paginas = ceil($total_filas / $por_pagina);

    // Limitamos la cantidad de botones mostrados
    $num_botones = 5; // Número de botones de paginación a mostrar
    $offset = floor($num_botones / 2); // Calculamos el desplazamiento

    // Calculamos el rango de botones a mostrar
    $inicio = max(1, $pagina_actual - $offset);
    $fin = min($total_paginas, $inicio + $num_botones - 1);

    if ($total_paginas > 1) {
        if ($pagina_actual > 1) {
            echo "<a href='?pagina=1'>Primera</a>";
            echo "<a href='?pagina=".($pagina_actual - 1)."'>Anterior</a>";
        }

        for ($i = $inicio; $i <= $fin; $i++) {
            if ($i == $pagina_actual) {
                echo "<a href='?pagina=$i' class='active'>$i</a>";
            } else {
                echo "<a href='?pagina=$i'>$i</a>";
            }
        }

        if ($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=".($pagina_actual + 1)."'>Siguiente</a>";
            echo "<a href='?pagina=$total_paginas'>Última</a>";
        }
    }
    ?>
</div>
</div>

<?php include("../../componentes/footer.php"); ?>