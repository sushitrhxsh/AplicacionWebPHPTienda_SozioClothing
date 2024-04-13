<?php 

require 'config/config.php';
require 'config/conexion.php';
require 'classes/clienteFunciones.php';

    $id = isset($_GET['id'])?$_GET['id']:'';
    $token = isset($_GET['token'])?$_GET['token']:'';

    if($id =='' || $token == ''){
        header('Location:index.php');
        exit;
    }

    $db = new Database();
    $conn  = $db->conectar();

    echo validaToken($id,$token,$conn);

?>