<?php session_start();

//lo primero es comprobar si el usuario tiene una session o no para saber a donde mandarlo

if (isset($_SESSION['usuario'])) {
	//si el usuario tiene sesion lo envio a contenido
	header('Location: contenido.php');
}else{
	//si no tiene sesion lo envio a la pagina de inicio de sesion
	header('Location: registrate.php');
}


?>