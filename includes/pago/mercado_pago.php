<?php 

// SDK de Mercado Pago
require 'vendor/autoload.php';
// Agrega credenciales
MercadoPago\SDK::setAccessToken(TOKEN_MP);

$preference = new MercadoPago\Preference();
$item = new MercadoPago\Item();
$item->id = '0001';
$item->title = 'Producto CDP';
$item->quantity = 1;
$item->unit_price = 150.00;
$item->currency_id = 'MXN';

$preference-> items = array($item);

$preference->back_urls = array(
    "success" => "http://localhost/tienda_ropa/captura.php",
    'failure' => "http://localhost/tienda_ropa/fallo.php"
);
$preference->auto_return = "approved";
$preference->binary_mode = true;
$preference->save();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- SDK MercadoPago.js-->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>

        <h3>Mercado Pago</h3>
        <div class="checkout-btn"></div>


        <script>
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