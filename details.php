<?php
require 'config/config.php'; 
require 'config/conexion.php';
//Declaracion de la conecxion de la base de datos
$db = new Database();
$conn  = $db->conectar();
//Declaracion si existe este id y su token
$id     = isset($_GET['id'])?$_GET['id']:'';
$token  = isset($_GET['token'])?$_GET['token']:'';

//un if que indique si el token esta vacio arroje una venan ade que el id o token esta mal
    if($id == '' || $token == ''){
        echo '<script type="text/javascript">alert("Error al procesar la peticion");</script>';
        exit;
    }else{
        $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
        //si el token es igual a token_tmp quiero que este token  haga un a consulta
        if($token == $token_tmp){

            $sql = $conn->prepare("SELECT count(*) FROM productos WHERE id_producto=? AND activo =1");
            $sql -> execute([$id]);
            if($sql ->fetchColumn() > 0){
                $sql = $conn->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id_producto=? AND activo =1 limit 1");
                $sql -> execute([$id]);
                $row = $sql ->fetch(PDO::FETCH_ASSOC);
                $nombre = $row['nombre'];
                $descripcion = $row['descripcion'];
                $precio = $row['precio'];
                $descuento = $row['descuento'];
                $precio_desc = $precio - (($precio * $descuento)/100);
                $dir_images = 'img/productos/'. $id .'/';
                
                $rutaimg = $dir_images .'principal.jpg';

                if(!file_exists($rutaimg)){
                    $rutaimg = 'img/no-photo.jpg';
                }

                $imagenes = array();
                if(file_exists($dir_images)){
                    $dir = dir($dir_images);

                    while(($archivo = $dir->read()) != false) {
                        if($archivo != 'principal.jpg' && (strpos($archivo,'jpg') || strpos($archivo,'jpeg'))){
                            $imagenes[] = $dir_images . $archivo;
                        }
                    }
                    $dir->close();
                }
                $sqlCaracter = $conn->prepare("SELECT distinct(det.id_caracteristica) as idCat,cat.caracteristica FROM det_prod_caracter AS det INNER JOIN caracteristicas AS cat ON (det.id_caracteristica = cat.id_caracteristica)WHERE det.id_producto=?");
                $sqlCaracter->execute([$id]);
            //}
            }else{
                echo '<script type="text/javascript">alert("Error al procesar la peticion");</script>';
                exit;
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
        <link rel="icon" type="image/jpg" href="/tienda_ropa/img/favicon/logo-favicon.svg">
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
        <main>

            <div class="container">
                <div class="row">
                    <div class="col-md-6 order-md-1">
                        <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                          <div class="carousel-inner">
                            <div class="carousel-item active">
                              <img src="<?php echo $rutaimg; ?>" class="d-block w-100">
                            </div>
                            <?php foreach($imagenes as $img){ ?>
                            <div class="carousel-item">
                                <img src="<?php echo $img; ?>" class="d-block w-100">
                            <div>
                            <?php } ?>
                          </div>
                          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>

                        
                    </div>
                    <div class="col-md-6 order-md-2">
                        <h2><?php echo $nombre; ?></h2>
                    <?php  if($descuento > 0){ ?>
                        <p><del><?php echo MONEDA . number_format($precio,2,'.',','); ?><del></p>
                        <h2>
                            <?php echo MONEDA ,number_format($precio_desc,2,'.',','); ?>
                            <small class="text-success"><?php echo $descuento  ?>% descuento</small>
                        </h2>
                    <?php }else{ ?>
                        <h2> <?php echo MONEDA ,number_format($precio,2,'.',','); ?></h2>
                    <?php } ?>

                        <p class="lead"><?php echo $descripcion; ?></p>
                        <div class="col-3 my-3">
                            <?php 
                            while($row_cat = $sqlCaracter->fetch(PDO::FETCH_ASSOC)){
                                $idCat = $row_cat['idCat'];
                                echo $row_cat['caracteristica']. ': '; 

                                echo "<select class='form-select' id='cat_$idCat'>";         

                                $sqlDet = $conn->prepare("SELECT id,valor,stock FROM det_prod_caracter  WHERE id_producto=? AND id_caracteristica=?");
                                    $sqlDet->execute([$id,$idCat]);
                                    while($row_det = $sqlDet->fetch(\PDO::FETCH_ASSOC)){
                                        echo '<option id="'.$row_det['id'].'">'.$row_det['valor'].'</option>';
                                    }
                                echo '</select>';
                            }?>


                        </div>
                        <div class="col-3 my-3">Cantidad:
                            <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="10" value="1">
                        </div>
                        <div class="d-grid gap-3 col-10 mx-auto">
                            <a href="pago.php" class="btn btn-primary" type="submit">Comprar</a>
                            <button class="btn btn-outline-primary" type="submit" onclick="addProducto(<?php echo $id; ?>,cantidad.value ,'<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                        </div>
                    </div>
                    
                </div>
            </div>
                
        </main>
    



    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>

    <script>
    /*let btnAgregar = document.getElementById("btnAgregar")
    let inputCantidad = document.getElementById("cantidad").value
    btnAgregar.onclick = addProducto(id,inputCantidad ,'token_tmp ?>')*/


        function addProducto(id,cantidad,token){
           let url = 'classes/carrito.php'
           let formData = new FormData()
           formData.append('id',id)
           formData.append('cantidad',cantidad)
           formData.append('token',token)

           fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data =>{
                    if(data.ok){
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero
                    }
            })
        }
    </script>

    </body>

</html>