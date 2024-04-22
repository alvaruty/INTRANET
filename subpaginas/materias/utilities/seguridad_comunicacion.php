<?php
//Inicio la sesi�n 
session_start(); 

//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO 
if ($_SESSION["autentificado"] != "SI" or ($_SESSION["permisos"] != "0")) { 
    //si no existe, envio a la p�gina de autentificacion 
    header("Location: utilities/control_acceso_comunicacion.php"); 
    //ademas salgo de este script 
    exit(); 
}
?> 