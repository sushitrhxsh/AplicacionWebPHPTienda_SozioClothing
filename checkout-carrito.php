<?php
require 'config/config.php';
require 'config/conexion.php';
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
    }
    //Destruye la sesion del carrito
    //session_destroy();
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
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Producto</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Cantidad</th>
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
                            ?>
                            <tr>
                                <td><?php echo $nombre; ?></td>
                                <td><?php echo MONEDA . number_format($precio_desc,2,',',','); ?></td>
                                <td>
                                    <input class="form-control" type="number" min="1" max="10" step="1" value="<?php echo $cantidad; ?>" 
                                    size="5" id="cantidad_<?php echo $_id;?>" onchange="actualizaCantidad(this.value,<?php echo $_id; ?>)">
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]" ><?php echo MONEDA . number_format($subtotal,2,',',','); ?></div>
                                </td>
                                <td>
                                    <a href="#" id="eliminar" class="btn btn-danger btn-sm" data-bs-id="<?php echo $_id; ?>" 
                                    data-bs-toggle="modal" data-bs-target="#eliminarModal">Eliminar</a>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2">
                            <p class="h3" id="total"><?php echo MONEDA . number_format($total,2,'.',',');?></p>
                                </td>
                            </tr>
                        </tbody>
                        <?php } ?>
                    </table>
                </div>
                <?php if($lista_carrito != null){ ?>
                <div class="row">
                    <div class="col-md-5 offset-md-7 d-grid gap-2">
                        <a href="pago.php" class="btn btn-outline-primary btn-lg">Realizar pago</a>
                    </div>
                </div>
                <?php } ?>
            </div>    
        </main>
        
        <!-- Modal delete -->
        <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="eliminarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eliminarModalLabel">Aviso</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Desea eliminar el producto de la lista?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        
        
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    <!-- Javascript -->
    <script type="text/javascript">
    
        let eliminaModal = document.getElementById('eliminarModal')
        eliminaModal.addEventListener('show.bs.modal', function(event){
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
            buttonElimina.value = id
        })


        function actualizaCantidad(cantidad,id){
            let url = 'classes/actualizar_carrito.php'
            let formData = new FormData()
            formData.append('action','agregar')
            formData.append('id',id)
            formData.append('cantidad',cantidad)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                //console.log(data); //para ver el dato data y si lo tiene
                if(data.ok){

                    let divsubtotal = document.getElementById('subtotal_' + id)
                    divsubtotal.innerHTML = data.sub
                    
                    let total = 0.00
                    let list = document.getElementsByName('subtotal[]')

                    for(let i = 0; i < list.length; i++){
                        total += parseFloat(list[i].innerHTML.replace(/[$,]/g, ''))
                    }

                    total = new Intl.NumberFormat('en-US',{
                        minimumFractionDigits: 2
                    }).format(total)

                    document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
            
                }
            })
        }

        function eliminar(){
            let botonElimina = document.getElementById('btn-elimina');
            let id = botonElimina.value

            let url = 'classes/actualizar_carrito.php'
            let formData = new FormData()
            formData.append('action','eliminar')
            formData.append('id',id)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                if(data.ok){
                    location.reload()
                    
                }
            })
        }

    </script>

    </body>

</html>