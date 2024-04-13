<?php
require 'config/config.php';
require 'config/conexion.php';
require 'classes/clienteFunciones.php';

$db = new Database();
$conn  = $db->conectar();

$errors = [];

if(!empty($_POST)){
    $email = trim($_POST['email']);

    if(esNulo([$email])){
        $errors[] = "Debe llenar todos los campos.";
    }

    if(!esEmail($email)){
        $errors[] = "La direccion de correo no es valida.";
    }

    if(count($errors) > 0){
        if(emailExiste($email, $conn)){
            $sql = $conn->prepare("SELECT usuarios.id_usuario FROM usuarios INNER JOIN clientes ON 
            usuarios.id_cliente=clientes.id_cliente WHERE clientes.email LIKE ? LIMIT 1");
            $sql->execute([$email]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id_usuario'];
            $nombres = $row['nombres'];
            
            $token = solicitaPassword($row['id_usuario'],$conn);
            
            if($token !== null){
                require "classes/mailer.php";
                $mailer = new Mailer();
               
                $asunto ="Recuperar password - sozioclothing";
                $cuerpo ="Estimado". $row['nombres'].": <br> Si has solicitado el cambio de tu contraseña da clic en el link <a href='$url'>$url</a>";
                $cuerpo.="<br> Si no hiciste esta solicitud puedes ignorar este correo.";
            
                if($mailer->enviarEmail($email,$asunto,$cuerpo)){
                    echo "<p><b>Correo enviado</b></p>";
                    echo "<p>Hemos enviando un correo electronico a $email para restablecer la contraseña.</p>";
                    exit;
                }
            } 
        }else{
            $errors[] ="No existe una cuenta asociada a esta direccion de correo electronico";
        }
    }
}

//Array para ver que hace por dentro
//print_r($_SESSION)

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
        <!--  CSS 
        <link href="css/estilos.css" rel="stylesheet" type="text/css">-->
        
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
        <main class="form-login m-auto pt-4">
            <h3>Recuperar contraseña</h3>

            <?php  mostrarMensajes($errors); ?>

            <form class="row g-3" action="recuperar.php" method="post" autocomplete="off">

                <div class="form-floating">
                    <input class="form-control" type="text" name="email" id="email" placeholder="Correo electronico">
                    <label for="email">Correo electronico</label>
                </div>
                <div class="d-grid gap-3 co-12">
                    <button  class="btn btn-dark" type="submit">Continuar</button>
                </div>
                <hr>
                <div class="col-12">
                    ¿no tienes una cuenta? <a href="registro.php">Registrate</a>
                </div>

            </form>
        </main>
    



    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    
   

    </body>

</html>