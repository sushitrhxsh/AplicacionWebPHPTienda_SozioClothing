<?php
//configuracion del sistema
//define("SITE_URL", "http://localhost/tienda_ropa");
define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA","$");

//Datos para token de MP,paypal
define("CLIENT_ID", "AQLPxwre9oRZ-R_32WOqH0AYttjO_ohpgr6B4r2spmILv20AZ7Ro_QcJUNG8pT3RZepcennAx-1ew16c");
define("TOKEN_MP","TEST-8544912749730753-052800-88a44b3cc28ffdbf572193cbc68c8d35-708793551");
define("CURRENCY", "MXN");

//Datos para el envio de correo electronico
//define("MAIL_USER", "l19260901@matamoros.tecnm.mx");
//define("MAIL_HOST","smtp.office365.com");
//define("MAIL_PASS", "xoq14770");
//define("MAIL_PORT", "465");

session_start();

$num_cart = 0;

if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}


?>