<?php
require 'config/config.php';
require 'config/conexion.php';
$db = new Database();
$conn  = $db->conectar();

$id_transaccion = isset($_GET['key'])? $_GET['key'] : 0;
$error ='';
if($id_transaccion == ''){
    $error = 'Error al procesar la peticion';
}else{

    $sql = $conn->prepare("SELECT count(*) FROM compra WHERE id_transaccion=? AND status =?");
    $sql -> execute([$id_transaccion,'COMPLETED']);
    if($sql ->fetchColumn() > 0){
        $sql = $conn->prepare("SELECT id_compra, fecha, email, total FROM compra WHERE id_transaccion=? AND status =? limit 1");
        $sql -> execute([$id_transaccion,'COMPLETED']);
        $row = $sql ->fetch(PDO::FETCH_ASSOC);
        
        $idCompra = $row['id_compra'];
        $total = $row['total'];
        $fecha = $row['fecha'];

        $sqlDet = $conn->prepare("SELECT nombre,precio,cantidad FROM detalle_compra WHERE id_compra = ?");
        $sqlDet -> execute([$idCompra]);
    }else{
        $error = 'Error al comprobar la compra';
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

        <!--contenido-->
        <main>
            <div class="container">
                <?php if(strlen($error) > 0){ ?>
                    <div class="rpw">
                        <div class="col">
                            <h3><?php echo $error; ?></h3>
                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="row">
                        <div class="col">
                            <b>Folio de la compra: </b><?php echo $id_transaccion; ?></b><br>
                            <b>Fecha de la compra: </b><?php echo $fecha; ?></b><br>
                            <b>Total: </b><?php echo MONEDA . number_format($total,2,'.',','); ?></b><br>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col">
                            <table class="table table-dark table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Producto</th>
                                        <th>Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)){ 
                                        $importe = $row_det['precio'] * $row_det['cantidad']  ?>
                                        <tr>
                                            <td><?php echo $row_det['cantidad']; ?></td>
                                            <td><?php echo $row_det['nombre']; ?></td>
                                            <td><?php echo $importe; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>  
                    </div>
                <?php } ?>
            </div>
        </main>

    </body> 

</html>