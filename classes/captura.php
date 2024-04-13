<?php 
require '../config/config.php';
require '../config/conexion.php';
$db = new Database();
$conn  = $db->conectar();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

/*echo "<pre>";
    print_r($datos);
echo "</pre>";*/

if(is_array($datos)){

    $id_transaccion = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos['detalles']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
    $email = $datos['detalles']['payer']['email_address'];
    $id_cliente = $datos['detalles']['payer']['payer_id'];

    $sql = $conn -> prepare("INSERT INTO compra (id_transaccion,fecha,status,email,id_cliente,total) VALUES(?,?,?,?,?,?)");
    $sql -> execute([$id_transaccion,$fecha,$status,$email,$id_cliente,$total]);
    $id = $conn -> lastInsertId();

    if($id > 0){
        $productos = isset($_SESSION['carrito']['productos'])?$_SESSION['carrito']['productos']: null;
        
        if($productos != null){
            foreach($productos as $clave => $cantidad){
    
                $sql = $conn->prepare("SELECT id_producto,nombre,precio,descuento FROM productos where id_producto=? and activo=1");
                $sql -> execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                $precio = $row_prod['precio'];
                $descuento = $row_prod['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);

                $sql_insert = $conn -> prepare("INSERT INTO detalle_compra (id_compra,id_producto,nombre,precio,cantidad) VALUES (?,?,?,?,?)");
                $sql_insert -> execute([$id,$clave,$row_prod['nombre'],$precio_desc,$cantidad]);
            }
            include "enviar_email.php"; //Aqui incluyo la libreria para que se envie por correo electronico
        }
        unset($_SESSION['carrito']);
    }
}



?>