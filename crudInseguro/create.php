<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nombre = $username = $password = $puesto = $salario = "";
$nombre_err = $username_err = $password_err = $salario_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validar nombre
    $input_nombre = trim($_POST["nombre"]);
    if(empty($input_nombre)){
        $nombre_err = "Por favor ingresa un nombre.";
    } elseif(!filter_var($input_nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nombre_err = "Por favor ingresa un nombre valido.";
    } else{
        $nombre = $input_nombre;
    }
    
    // Validar username
    $input_username = trim($_POST["username"]);
    if(empty($input_username)){
        $username_err = "Por favor ingresa un username.";
    } else {
        $username = $input_username;
    }

    // Validar contrasena
    $input_password = trim($_POST["password"]);
    if(empty($input_password)){
        $password_err = "Por favor ingresa una contrasena.";     
    } elseif(strlen($input_password) < 8 ) {
        $password_err = "Por favor ingresa una contrasena de minimo 8 caracteres.";
    } else{
        $password = $input_password;
    }

    // Validar puesto
    $input_puesto = trim($_POST["puesto"]);
    if(empty($input_puesto)){
        $puesto_err = "Por favor ingresa una puesto.";     
    } else{
        $puesto = $input_puesto;
    }
    
    // Validar salario
    $input_salario = trim($_POST["salario"]);
    if(empty($input_salario)){
        $salario_err = "Por favor ingresa un monto de salario.";     
    } elseif(!ctype_digit($input_salario)){
        $salario_err = "Por favor ingresa un numero positivo.";
    } else{
        $salario = $input_salario;
    }
    
    // Check input errors before inserting in database
    if(empty($nombre_err) && empty($username_err) && empty($salario_err) && 
        empty($password_err) && empty($puesto_err) ){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (nombre, username, password, puesto, salario) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){

            // Set parameters
            $param_nombre = $nombre;
            $param_username = $username;
            $param_salario = $salario;
            $param_puesto = $puesto;
            $param_password = $password;

            
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_nombre, $param_username, $param_password, $param_puesto, $param_salario);
            
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: home.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Agregar Empleado</h2>
                    <p>Por favor llena el formulario para agregar el empleado.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control <?php echo (!empty($nombre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nombre; ?>">
                            <span class="invalid-feedback"><?php echo $nombre_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Contrasena</label>
                            <input name="password" type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Puesto</label>
                            <input name="puesto" class="form-control <?php echo (!empty($puesto_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $puesto; ?>">
                            <span class="invalid-feedback"><?php echo $puesto_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Salario</label>
                            <input type="text" name="salario" class="form-control <?php echo (!empty($salario_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salario; ?>">
                            <span class="invalid-feedback"><?php echo $salario_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Registrar">
                        <a href="home.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>