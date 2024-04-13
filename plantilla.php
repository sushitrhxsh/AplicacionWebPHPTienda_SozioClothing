<?php
require 'config/config.php';
require 'config/conexion.php';
require 'classes/clienteFunciones.php';

$db = new Database();
$conn  = $db->conectar();

$errors = [];

if(!empty($_POST)){

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if(esNulo([$nombres,$apellidos,$email,$telefono,$dni,$usuario,$password,$repassword])){
        $errors[] = "Debe llenar todos los campos.";
    }
    if(!esEmail($email)){
        $errors[] = "La direccion de correo no es valida.";
    }
    if(!validaPassword($password,$repassword)){
        $errors[] = "Las contraseñas no coinciden.";
    }
    if(usuarioExiste($usuario,$conn)){
        $errors[] = "El nombre de usuario: $usuario ya existe.";
    }
    if(emailExiste($email,$conn)){
        $errors[] = "El correo electronico: $email ya existe.";
    }

}
?>

<!doctype html>
<html lang="es">

    <head>
    <title>Sozio Clothing Co</title>
    

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
        <!-- Favicon logo html  -->
        <link rel="icon" type="image/jpg" href="img/favicon/logo-favicon.svg">
        <!--  CSS  -->
        <link href="css/estilos.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <header>
            <div class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                <img src="img/favicon/logo_sozioheader.png"  width="60" alt="Sozio Clothing logo">
                    <a href="index.php" class="navbar-brand">
                        <strong>Sozio Clothing</strong>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="تبديل التنقل">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarHeader">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="#"><i class="fas fa-home">Catalogo</i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-home">Contactanos</i></a>
                            </li>
                        </ul>
                        <a class="btn btn-warning" href="checkout-carrito.php">Carrito <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?><span></a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Contenido -->
        <main>
            <div class="container">
                
            </div>     
        </main>
    



    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    
   

    </body>

</html>