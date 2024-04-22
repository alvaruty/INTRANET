<?php 
session_start(); 
include("../../base.php");
include ("utilities/seguridad_comunicacion.php");
include("../../componentes/header.php"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso denegado : IES Las Salinas</title>
    <link rel="icon" href="/../imagenes/logo-sinFondo.png" type="image/x-icon">
    <style>
        .centrado {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh; /* Esto centrará los elementos verticalmente en toda la altura de la ventana */
        margin-top:-80px;
        }

        .centrado p {
            color:2496ca;
        }

        /* icono atras */
        .img {
            margin-left: 10%; 
            padding-top: 15px;
        }

    </style>
</head>
<body>
    <div class="img">
        <a href="../materias.php"><img src="../../../imagenes/hacia-atras.png" width="30" height="auto"></a>
    </div>
    <section class="centrado">
        <p>
        <?php 
            //tomo el valor de un elemento de tipo texto del formulario 
            $tipo = $_POST["tipo_comunicacion"]; 
            $texto = $_POST["texto_comunicacion"]; 
            $remitente=$_SESSION["usuario"];
            $fecha=date("Y-m-d");
            $hora=date("H:i:s");
            $profesor_destino=$_POST["desplegable_profesores"]; 
            echo "Escribió en el campo de texto: " . $texto. "<br><br>"; 
            //Ahora insertamos la nueva comunicacion
            $cadenaSQL="insert into comunicaciones ( cd_comunicacion, texto, fecha, hora, usuario_remitente, usuario_destino, estado ) ";
            $cadenaSQL=$cadenaSQL." values ($tipo, '$texto', '$fecha', '$hora','$remitente','$profesor_destino',0)";
            //echo $cadenaSQL;
            $result=mysqli_query($conexion, $cadenaSQL);
            if ($result ==1){
                echo "La comunicación se ha enviado correctamente.";
            }
            else{	
                echo "Error al guardar la comunicación.";	
            }
            mysqli_close($conexion);
        ?>
        </p>
    </section>
</body>

<?php 
include("../../componentes/footer.php"); 
?>