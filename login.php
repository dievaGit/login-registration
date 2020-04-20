<?php session_start();//este archivo se va a encargar de llevar toda la logica de inicio de sesion del usuario en el sitio


if (isset($_SESSION['usuario'])) {
	//si el usuario tiene sesion iniciada lo envio al index
	header('Location: index.php');
}

$errores = '';

//comprobando si el usuario envio los datos que puso
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//limpiando los datos y haciendolo minuscula
	$usuario = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
	$password = $_POST['password'];

	//ENCRIPTANDO LA CONTRASEñA
	$password = hash('sha512', $password);

	//CONECTANDO A LA bd
	try {
		$conexion = new PDO('mysql:host=localhost;dbname=login_practica', 'root', '');
	} catch (PDOException $e) {
		echo "Error:" . $e->getMessage();
	}
    
    //preparando la consulta donde el usuario y la contraseña de la BD sea igual a la que el cliente entra
	$statement = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario AND pass = :password');
    
    //haciendo la consulta
    $statement->execute(array(':usuario' => $usuario, ':password' => $password));

    //guardando los datos fetch devuelve el resultado
    $resultado = $statement->fetch();

    //si todo esta correcto agragamos la session y mandamos al index q manda al contenido
    if ($resultado !== false) {
    	$_SESSION['usuario'] = $usuario;
    	header('Location: index.php');
    }else{
    	$errores .= '<li>Datos Incorrectos</li>';
    }
     
    // print_r($resultado);

    //
}

//llamando al view del formulario
require 'views/login.view.php';

?>