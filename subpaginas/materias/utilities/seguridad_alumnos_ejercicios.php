<?php
//Inicio la sesi�n 
session_start(); 

//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO 
//Descartamos la subida de ejercicios de los padres (Nivel de permisos=0)
if (($_SESSION["autentificado"] != "SI")or ($_SESSION["permisos"] < "1")) { 
    //si no existe, envio a la p�gina de autentificacion 
    header("Location: utilities/control_acceso_alumnos.php"); 
    //ademas salgo de este script 
    exit(); 
}else{
	if ($_SESSION["cd_departamento"] == ''){
		//Si no tiene almacenado el c�digo de departamento debe volver a seleccionarlo
		header("Location: utilities/sesion_caducada.php"); 
	}
} 
?> 