<?php
require 'config/config.php';
require 'config/conexion.php';
require 'vendor/autoload.php'; 
MercadoPago\SDK::setAccessToken(TOKEN_MP);

$preference = new MercadoPago\Preference();
$productos_mp = array();


$db = new Database();
$conn  = $db->conectar();

    $productos = isset($_SESSION['carrito']['productos'])?$_SESSION['carrito']['productos']: null;

    //Array para ver que hace por dentro
    //print_r($_SESSION);
    //Declaraciones
    $lista_carrito = array();

    if($productos != null){
        foreach($productos as $clave => $cantidad){

            $sql = $conn->prepare("SELECT id_producto,nombre,precio,descuento, $cantidad as cantidad FROM productos where id_producto=? and activo=1");
            $sql -> execute([$clave]);
            $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);

        }
    }else{
        header("Location: index.php");
        exit;
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
        <!-- SDK MercadoPago.js-->
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <!--  Paypal Boton  -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?PHP echo CURRENCY; ?>"></script>
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
                <div class="row">

                    <div class="col-6">
                    <h4>Detalles de pago</h4>
                        <div class="row">
                            <div class="col-12">
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="checkout-btn"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        if($lista_carrito == null){
                                            echo '<tr><td colspan="5" class="text-center"><b>No hay productos en el carrito 🐱‍💻</b></td></tr>';
                                        }else{

                                            $total=0;
                                            foreach($lista_carrito as $producto){
                                                $_id = $producto['id_producto'];
                                                $nombre = $producto['nombre'];
                                                $precio = $producto['precio'];
                                                $descuento = $producto['descuento'];
                                                $cantidad = $producto['cantidad'];      
                                                $precio_desc = $precio - (($precio * $descuento) / 100);
                                                $subtotal = $cantidad * $precio_desc;
                                                $total += $subtotal;     

                                                $item = new MercadoPago\Item();
                                                $item->id = $_id;
                                                $item->title = $nombre;
                                                $item->quantity = $cantidad;
                                                $item->unit_price = $precio_desc;
                                                $item->currency_id = 'MXN';

                                                array_push($productos_mp,$item);
                                                unset($item);
                                    ?>
                                    <tr>
                                        <td><?php echo $nombre; ?></td>
                                        <td>
                                            <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]" ><?php echo MONEDA . number_format($subtotal,2,',',','); ?></div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <p class="h3 text-end" id="total"><?php echo MONEDA . number_format($total,2,'.',',');?></p>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php } ?>
                            </table>
                        </div> 
                    </div> 
                                            
                </div>
            </div> 
        </main>
<?php 
$preference->items = $productos_mp;
$preference->back_urls = array(
    "success" => "http://localhost/tienda_ropa/captura.php",
    'failure' => "http://localhost/tienda_ropa/fallo.php"
);

$preference->auto_return = "approved";
$preference->binary_mode = true;
$preference->save();

    
?>


    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    
    <script>
    //Boton de paypal
        paypal.Buttons({
            style:{
                color:'blue',
                shape: 'pill',
                label:'pay'
            },
            createOrder: function(data,actions){
                return actions.order.create({
                    purchase_units:[{
                        amount: {
                            value: <?php echo $total;?>
                        } 
                    }]
                });
            },
            onApprove: function(data, actions){
                let URL = "classes/captura.php" 
                actions.order.capture().then(function (detalles){
                    console.log(detalles)

                    return fetch(URL,{
                        method: 'POST',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            detalles: detalles
                        })
                    }).then(function(response) {
                        window.location.href = "completado.php?key=" + detalles['id'];
                    })

                });
            },
            onCancel: function(data){
                alert("Pago Cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container');
        
    //Boton Mercado pago
        const mp = new MercadoPago('TEST-3fb31ae2-c4a4-4832-ae6d-9cadb40da306',{
            locale: 'es-MX'
        });
        mp.checkout({
            preference:{
                id: '<?php echo $preference->id; ?>'
            },
            render:{
                container: '.checkout-btn',
                label: 'Pagar con Mercado Pago'
            }
        })


    </script>




    </body>

</html>