<?php session_start();

//destruyendo la sesion del usuario
session_destroy();

//limpiando la session
$_SESSION = array();

header('Location: login.php');

?>