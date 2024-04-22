<?php 
	include ("../base.php");	
	//include ("cadenas.php");
	//vemos si el usuario y contraseña es váildo 
	$contador=0;
	$usuario_interno=$_POST["usuario"];
	//$usuario_interno=cambia_comillas($usuario_interno);
	$contraseña_interna=$_POST["contrasena"];

	//echo "SELECT ultimo_acceso, nivel_permisos, nombre, apellidos, cd_usuario, cd_departamento FROM usuarios where usuario='$usuario_interno' and contrasena='$contraseña_interna'"; 
	$result=mysqli_query($conexion,"SELECT ultimo_acceso, nivel_permisos, nombre, apellidos, cd_usuario, cd_departamento FROM usuarios where usuario='$usuario_interno' and contrasena='$contraseña_interna'" );

	while($row=mysqli_fetch_row($result)){
			$contador=	$contador+1;
			$fecha_ultima=$row[0];
			$nivel_permisos = $row[1];
			$nombre_usuario=$row[2];
			$apellido_usuario=$row[3];
			$cod_usuario=$row[4];
			$cd_departamento=$row[5];
	}
	if ($contador==1){ 
	    //echo "llega hasta aquí";
	    //usuario y contraseña válidos 
	    //defino una sesion y guardo datos 
	    session_start(); 
	    $fecha_de_hoy =date("Y-m-d H:i:s");
    	$result=mysqli_query($conexion, "UPDATE usuarios SET ultimo_acceso = '$fecha_de_hoy' where cd_usuario=$cod_usuario ");
    	//echo "UPDATE usuarios SET ultimo_acceso = '$fecha_de_hoy' where cd_usuario=$cod_usuario ";
     	if ($nivel_permisos <=1){     
		    $fecha =date("Y-m-d");
	    	$hora=date("H:i:s");
	    	$sql="insert into accesos (usuario, fecha, hora) values ('$usuario_interno', '$fecha', '$hora')";	
	   		$result=mysqli_query($conexion,$sql );   	
	   	}	
	    $_SESSION["autentificado"]= "SI"; 
   	    $_SESSION["ultimo_acceso"]= $fecha_ultima;
   	    $_SESSION["nombre_u"]= $nombre_usuario;
   	    $_SESSION["apellido_u"]= $apellido_usuario;
   	    $_SESSION["usuario"]= $usuario_interno;
   	    $_SESSION["permisos"]=$nivel_permisos; 
   	    $_SESSION["cd_departamento_G"]=$cd_departamento; 
	    header ("Location: ../index.php");
	}else { 
	    //si no existe le mando otra vez a la portada 
	    header("Location: ../index.php?errorusuario=si"); 
	} 
	mysqli_close($conexion); 
?> 
