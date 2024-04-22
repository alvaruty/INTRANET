<?php
    include ("../../base.php");
	include ("utilities/seguridad.php");
	//Recogemos el parámetro que él mismo se envía para ordenar los ejercicios
	$ordenar_por=$_POST["ordenar_por"];
	$agrupaciones=$_POST["agrupaciones"];
	$ocultar_corregidos=$_POST["ocultar_corregidos"];
	$cd_enlace=$_POST["cd_enlace"];
	$php_de_origen=$_POST["php_de_origen"];
	$accion=$_POST["accion"];
	$hoy=date("Y-m-d");
	if ($accion==1){
		$cadenaSQL="update enlaces_ejercicios set revisado='$hoy' where cd_enlace=".$cd_enlace;
	}else{
		$cadenaSQL="update enlaces_ejercicios set revisado = NULL where cd_enlace=".$cd_enlace;
	}
	//echo $cadenaSQL;
	$result=mysqli_query($conexion, $cadenaSQL);
	mysqli_close($conexion);

	//include("../../componentes/header.php");
?> 
<body onload="form.submit()">
<form name ="form" action ="<?php echo $php_de_origen; ?>" method="POST">
<input type="hidden" name="ordenar_por" size="20" value="<?php echo $ordenar_por; ?>">
<input type="text" name="agrupaciones" size="20" value="<?php echo $agrupaciones; ?>">
<input type="hidden" name="ocultar_corregidos" size="20" value = "<?php echo $ocultar_corregidos; ?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center">Guardando revisión...</p>
</body>