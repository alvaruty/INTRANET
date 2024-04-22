<?php
		session_start(); 
	    $_SESSION["autentificado"]= "NO"; 
   	    $_SESSION["ultimo_acceso"]= '';
   	    $_SESSION["nombre_u"]= '';
   	    $_SESSION["apellido_u"]= '';
   	    $_SESSION["usuario"]= '';
   	    $_SESSION["permisos"]=0; 
   	    $_SESSION["cd_departamento_G"]=0; 
		session_destroy();

?>
<html>

<head>
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sesi? cerrada</title>
</head>

<body>

<p align="center"><font face="Tahoma" color="#000080">Su sesión ha sido cerrada 
correctamente</font></p>

<p align="center"><font face="Tahoma" color="#000080">
<a target="_top" href="../index.php">PROMETEO</a></font></p>

</body>

</html>