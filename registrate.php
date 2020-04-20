<?php session_start(); //este archivo se va a encargar de llevar toda la logica del registro de usuario en el sitio


//lo primero es comprobar si el usuario tiene una session o no para saber a donde mandarlo

if (isset($_SESSION['usuario'])) {
	//si el usuario tiene sesion lo envio al index
	header('Location: index.php');
}

//Aqui recibiendo los datos de sesion que envio con SERVER PHP SELF. si el metodo de envio es igual a POST entonces los datos se enviaron
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//aqui recibo los datos que estoy pasando por el name en el view
	//con stringtolower convertimos todo a minuscula para ponerlo en la base de datos
	//y con el filter lo limpiamos
	$usuario = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
	$password = $_POST['password'];
	$password2 = $_POST['password2'];

	
	$errores = '';

   //verificando que el usuario y contraseñas no esten vacias. que el usuario haya escrito datos en el input
	if(empty($usuario) or empty($password) or empty($password2)) {
	   $errores .= '<li>Por favor rellena los datos correctamente</li>'; 
	} else{
		
		try{
           $conexion = new PDO('mysql:host=localhost;dbname=login_practica', 'root', '');
		} catch (PDOException $e){
			echo "Error: " . $e->getMessage();
		}
        
        //lo primero es comprobar que el usuario no exista ya, aqui digo que traiga el campo igual al usuario que le paso
        $statement = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');

        //ejecutando nuestra consulta sql diciendole que queremos que usuario sea igual a la variable usuario
        $statement->execute(array(':usuario' => $usuario));
        
        //fetch puede devolver el usuario que ya existe en la BD o false
        $resultado = $statement->fetch();

        //si el usuario existe tengo que enviar un mensaje en pantalla
        if($resultado != false) {
        	$errores .= '<li>El nombre de usuario ya existe</li>';
        }

        //ENCRIPTANDO LA CONTRASEñA
        $password = hash('sha512', $password);
        $password2 = hash('sha512', $password2);
       
        //verificando que las 2 contraseñas sean iguales al registrarse
        if($password != $password2){
        	$errores .= '<li>Las contraseñas no son iguales</li>';
        }
	}

	//Ya aqui si no hay ningun error entonces agregamos el usuario a la base de datos y enviamos al cliente a iniciar sesion en el sitio
	if($errores == ''){
		$statement = $conexion->prepare('INSERT INTO usuarios (id, usuario, pass) VALUES (null, :usuario, :pass)');
		$statement->execute(array(':usuario' => $usuario, ':pass' => $pass));

		//redirigiendo al cliente a login.php
		header('Location: login.php');
	}

}

//llamando al view del formulario
require 'views/registrate.view.php';

?>