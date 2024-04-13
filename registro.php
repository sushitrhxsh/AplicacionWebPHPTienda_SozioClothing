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

    if(count($errors) == 0){

        $id = registraCliente([$nombres,$apellidos,$email,$telefono,$dni],$conn);

        if($id > 0){
            require "classes/mailer.php";
            $mailer = new Mailer();
            $token = generarToken(); 
            $pass_hash = password_hash($password,PASSWORD_DEFAULT);

            $idUsuario = registraUsuario([$usuario,$pass_hash,$token,$id],$conn);
            if($idUsuario > 0){
                $url = 'http://localhost/tienda_ropa/activar_cliente.php?id='.$idUsuario.'&token='.$token;
                $asunto ="Activar cuenta - sozioclothing";
                $cuerpo ="Estimado $nombres: <br> Para continuar con el proceso es indispensable dar click en el siguiente link <a href='$url'>Activar cuenta</a>";

                if($mailer->enviarEmail($email,$asunto,$cuerpo)){
                    echo "Para terminar el proceso de registro siga con las instructucciones que le hemos enviado a la direccion de correo electronico $email";
                    exit;
                }

            }else{
                $errors[] = "Error al registrar usuario";
            }          

        }else{
            $errors[] = "Error al registrar cliente";
        }
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
        <main>
            <div class="container">
                <h2>Datos del cliente</h2>
                <?php mostrarMensajes($errors); ?>

                <form class="row g-3" action="registro.php" method="post" autocomplete="off">
                    <div class="col-md-6">
                        <label for="nombres"><span class="text-danger">*</span> Nombres</label>
                        <input class="form-control" type="text" name="nombres" id="nombres" requireda>
                    </div>
                    <div class="col-md-6">
                        <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                        <input class="form-control" type="text" name="apellidos" id="apellidos" requireda>
                    </div>
                    <div class="col-md-6">
                        <label for="email"><span class="text-danger">*</span> Correo electronico</label>
                        <input class="form-control" type="email" name="email" id="email" requireda>
                        <span id="validaEmail" class="text-danger"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="telefono"><span class="text-danger">*</span> Telefono</label>
                        <input class="form-control" type="tel" name="telefono" id="telefono" requireda>
                    </div>
                    <div class="col-md-6">
                        <label for="dni"><span class="text-danger">*</span> DNI</label>
                        <input class="form-control" type="text" name="dni" id="dni" requireda>
                    </div>
                    <div class="col-md-6">
                        <label for="usuario"><span class="text-danger">*</span> Usuario</label>
                        <input class="form-control" type="text" name="usuario" id="usuario" requireda>
                        <span id="validaUsuario" class="text-danger"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="password"><span class="text-danger">*</span> Contraseña </label>
                        <input class="form-control" type="password" name="password" id="password" requireda>
                    </div>
                    <div class="col-md-6">
                        <label for="repassword"><span class="text-danger">*</span> Repetir contraseña</label>
                        <input class="form-control" type="password" name="repassword" id="repassword" requireda>
                    </div>

                    <i><b>Nota:</b> Los campos con asterisco son obligatorios</i>
                    <div class="col-12"> 
                        <button class="btn btn-outline-success" type="submit">Registrar</button>
                    </div>

                </form>
            </div>     
        </main>
    



    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    
    <script>
        let txtUsuario = document.getElementById('usuario')
        txtUsuario.addEventListener("blur",function(){
            existeUsuario(txtUsuario.value)
        },false)

        let txtEmail = document.getElementById('email')
        txtEmail.addEventListener("blur",function(){
            existeEmail(txtEmail.value)
        },false)


        function existeUsuario(usuario){
            let url = "classes/clienteAjax.php"
            let formData = new FormData()
            formData.append("action","existeUsuario")
            formData.append("usuario",usuario)

            fetch(url,{
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data =>{
                if(data.ok){
                    document.getElementById('usuario').value = ''
                    document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
                }else{
                    document.getElementById('validaUsuario').innerHTML = ''
                }
            })
        }

        function existeEmail(email){
            let url = "classes/clienteAjax.php"
            let formData = new FormData()
            formData.append("action","existeEmail")
            formData.append("email",email)

            fetch(url,{
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data =>{
                if(data.ok){
                    document.getElementById('email').value = ''
                    document.getElementById('validaEmail').innerHTML = 'Email no disponible'
                }else{
                    document.getElementById('validaEmail').innerHTML = ''
                }
            })
        }

    </script>

    </body>

</html>