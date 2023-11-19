<?php

session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $credenciales_err =  "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    
    // Validar username
    $input_username = trim($_POST["username"]);
    if(empty($input_username)){
        $username_err = "Por favor ingresa usuario.";
    }  else {
        $username = $input_username;
    }

    // Validar contrasena
    $input_password = trim($_POST["password"]);
    if(empty($input_password)){
        $password_err = "Por favor ingresa una contrasena.";     
    } elseif(strlen($input_password) < 8 ) {
        $password_err = "Por favor ingresa una contrasena de minimo 8 caracteres..";
    } else{
        $password = $input_password;
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err)  ){
        // Prepare an insert statement
        $sql = "SELECT id, username FROM employees WHERE username=? AND password=?";
         
        if($stmt = mysqli_prepare($link, $sql)){

            // Set parameters
            $param_username = $username;
            $param_password = $password;

            
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Verificar si se recuperaron filas
                mysqli_stmt_store_result($stmt);
                
                // Verificar el número de filas recuperadas
                if(mysqli_stmt_num_rows($stmt) > 0) {

                    mysqli_stmt_bind_result($stmt, $id, $username);
                    mysqli_stmt_fetch($stmt);
                    // Verificar si el nombre de usuario cumple con ciertos criterios
                    if ($username == 'UserRH' || $username == 'UserFinza') {

                        // Almacenar la identificación del usuario en la sesión
                        $_SESSION['id'] = $username;

                        // Redirigir a la página principal
                        header("location: home.php");
                        exit();
                    } else {
                        // Nombre de usuario no permitido, mostrar un mensaje de error
                        echo "<script>alert('Acceso denegado, no tienes permisos de ingresar.')</script>";
                    }
                } else {
                    // No se encontraron registros, mostrar un mensaje de error
                    echo "<script>alert('Error, credenciales incorrectas')</script>";
                }
            } else {
                echo "<script>alert('Oops!Algo malo ocurrio al procesar la solicitud, intentalo mas tarde.')</script>";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-5 p-5 offset-md-4 form login-form">
                <img src="softwareTC.jpg" style="width: 100%" class=""/>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2 class="text-center">Iniciar Sesion</h2>
                    <p class="text-center">Ingresa tu usuario y contrasena.</p>
                    
                    <div class="form-group">
                        <label>Ingresa el usuario</label>
                        <input class="form-control" type="text" name="username" placeholder="Username" required>
                        <span class="invalid-feedback"><?php echo $username_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Ingresa tu contrasena</label>
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                        <span class="invalid-feedback"><?php echo $password_err;?></span>
                    </div>
                    <div class="form-group">
                        <input class="form-control button bg-primary text-white" type="submit" name="login" value="Login">
                    </div>
                    <span class="invalid-feedback"><?php echo $_SESSION;?></span>
                </form>
            </div>
        </div>
    </div>
    <footer class="bg-light text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-5" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2023 Copyright: SoftwareTC.com
            <a href="https://www.facebook.com/FEIUV" class="me-4 link-secondary">
                <img src="facebook_logo.png" class="float-right" style="width: 3%;">
            </a>
        </div>
        
        <!-- Copyright -->
    </footer>
    
</body>
</html>