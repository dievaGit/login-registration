<?php session_start();

//comprobando que el usuario tenga session para que pueda aceder al contenido
//protego el contenido
if (isset($_SESSION['usuario'])) {
	//si el usuario tiene sesion iniciada lo envio al index
	require 'views/contenido.view.php';
} else{
	header('Location: login.php');
}

?>