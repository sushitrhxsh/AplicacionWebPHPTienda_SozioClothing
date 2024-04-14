<?php
require 'config/config.php';
require 'config/conexion.php';
require 'classes/clienteFunciones.php';

$db = new Database();
$conn  = $db->conectar();
$errors = [];

if(!empty($_POST)){

    $user_id = trim($_POST['usuario']);
    $password = trim($_POST['update-pass']);
    $confirm_password = trim($_POST['update-pass-confirm']);

    if(esNulo([$confirm_password,$confirm_password])){
        $errors[] = "Debe llenar todos los campos.";
    }
    if(!validaPassword($password,$confirm_password)){
        $errors[] = "Las contraseñas no coinciden.";
    }
    if(count($errors) == 0){
        $pass_hash = password_hash($password,PASSWORD_DEFAULT);
        cambioPassword($user_id,$confirm_password,$conn);
    }

}

//Array para ver que hace por dentro
//print_r($_SESSION);
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    </head>
    <style>
        .form-login{
            max-width: 350px;
        }
    </style>

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
                                <a class="nav-link active" href="index.php">Catalogo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contactanos.php">Contactanos</i></a>
                            </li>
                            <li class="nav-item">
                                <div class="dropdown">
                                  <a class="btn btn-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Temas
                                  </a>

                                  <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="./pdf/RTV-19260966-Unidad1.pdf">Tema 1</a></li>
                                    <li><a class="dropdown-item" href="./pdf/RTV-19260966-Unidad2.pdf">Tema 2</a></li>
                                    <li><a class="dropdown-item" href="./pdf/RTV-19260966-Unidad3.pdf">Tema 3</a></li>
                                    <li><a class="dropdown-item" href="./pdf/RTV-Unidad4.pdf">Tema 4</a></li>
                                  </ul>
                                </div>
                            </li>
                        </ul>
                        <a class="btn btn-warning me-2" href="checkout-carrito.php"><i class="bi bi-cart3"></i><span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?><span></a>
                        
                        <?php if(isset($_SESSION['user_id'])){ ?>
                                <div class="dropdown">
                                <a class="btn btn-light dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-heart"> <?php echo $_SESSION['user_name'];?></i></a>

                                  <ul class="dropdown-menu">
                                    <li><p style="text-align: center;"><i class="bi bi-person-circle"> <?php echo $_SESSION['user_name'];?></i></p></li>
                                    <li><a class="dropdown-item" href="./recupera.php"><i class="bi bi-gear"> Cambiar contraseña</i></a></li>
                                    <li><a class="dropdown-item" href="./logout.php"><i class="bi bi-box-arrow-left"> Cerrar sesion</i></a></li>
                                  </ul>
                                </div>
                            
                        <?php }else{ ?>
                            <a class="btn btn-light"  href="login.php"><i class="bi bi-person-fill"> Ingresar</i></a>
                        <?php }?>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Contenido -->
        <main class="form-login m-auto pt-4">
            <h2>Cambiar contraseña</h2>
            <?php  mostrarMensajes($errors); ?>

            <form class="row g-3" action="recupera.php" method="post" autocomplete="off">
                <input type="hidden" value="<?php echo $_SESSION["user_id"]; ?>" name="usuario">
                <div class="form-floating">
                    <input class="form-control" type="password" name="update-pass" id="update-pass" placeholder="Contraseña nueva" maxlength="12">
                    <label for="update-pass">Nueva contreseña</label>
                </div>
                <div class="form-floating">
                    <input class="form-control" type="password" name="update-pass-confirm" id="update-pass-confirm" placeholder="Confirme su nueva contraseña" maxlength="12">
                    <label for="update-pass-confirm">Confirme nueva contraseña</label>
                </div>
                <hr>
                <span class="text-primary">Se cerrara la sesi&oacute;n al cambiar su contrase&ntilde;a.</span>
   
                <div class="d-grid gap-3 co-12">
                    <button  class="btn btn-dark" type="submit">Confirmar</button>
                </div>
            </form>
            
        </main>
    

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    
    </body>

</html>