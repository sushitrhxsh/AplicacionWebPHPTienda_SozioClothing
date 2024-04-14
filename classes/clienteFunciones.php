<?php 
/***********************************************************
 * FUNCIONES PARA VALIDAR Y GENERAR                        *
 ***********************************************************/
function esNulo(array $parametros){
    foreach($parametros as $parametro){
        if(strlen(trim($parametro)) < 1){
            return true;
        }
    }
    return false;
}

function esEmail($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

function validaPassword($password,$repassword){
    if(strcmp($password,$repassword) === 0){
        return true;
    }
    return false;
}

function generarToken(){
    return md5(uniqid(mt_rand(),false));
}

/*************************************************************
 *              FUNCIONES PARA SQL                           *
 *************************************************************/
function registraCliente(array $datos, $conn){
    $sql = $conn->prepare("INSERT INTO clientes (nombre,apellidos,email,telefono,dni,estatus,fecha_alta) VALUES (?,?,?,?,?,1,now())");
    if($sql->execute($datos)){
        return $conn->lastInsertId();
    }
    return 0;
}

function registraUsuario(array $datos,$conn){
    $sql = $conn->prepare("INSERT INTO usuarios(usuario,password,token,id_cliente) VALUES (?,?,?,?)");
    if($sql->execute($datos)){
        return $conn->lastInsertId();
    }
    return 0;
}

function usuarioExiste($usuario,$conn){
    $sql = $conn->prepare("SELECT id_usuario FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if($sql->fetchColumn() > 0){
        return true;
    }
    return false;
}

function emailExiste($email,$conn){
    $sql = $conn->prepare("SELECT id_cliente FROM clientes WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    if($sql->fetchColumn() > 0){
        return true;
    }
    return false;
}

function mostrarMensajes(array $errors){
    if(count($errors) > 0){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach($errors as $error){
            echo '<li>'.$error.'</li>';
        }
        echo'</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

function validaToken($id,$token,$conn){
    $msg='';
    $sql = $conn->prepare("SELECT id_usuario FROM usuarios WHERE id_usuario=? and token LIKE ? LIMIT 1");
    $sql->execute([$id,$token]);
    if($sql->fetchColumn() > 0){
        if(activaUsuario($id,$conn)){
            $msg ='Cuenta activada';
        }else{
            $msg ='Error al activar la cuenta';
        }
    }else{
        $msg="No existe el registro del cliente.";
    }
    return $msg;
}

function activaUsuario($id,$conn){
    $sql = $conn->prepare("UPDATE usuarios SET activacion=1, token='' WHERE id_usuario=?");
    return $sql->execute([$id]);
}

function login($usuario,$password,$conn){
    $sql = $conn->prepare("SELECT id_usuario,usuario,password FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql -> execute([$usuario]);

    if($row = $sql->fetch(PDO::FETCH_ASSOC)){

        if(esActivo($usuario,$conn)){
            if(password_verify($password, $row['password'])){
                $_SESSION['user_id'] = $row['id_usuario'];
                $_SESSION['user_name'] = $row['usuario'];
                header('Location: index.php');
                exit;
            }

        }else{
            return 'El usuario no ha sido activado.';
        }
    }
    return 'El usuario y/o contraseña son incorrectos.';
}

function esActivo($usuario,$conn){
    $sql = $conn->prepare("SELECT activacion FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row['activacion'] == 1){
        return true;
    }
    return false;
}

function solicitaPassword($user_id,$conn){
    $token = generarToken();
    
    $sql = $conn->prepare("UPDATE usuarios SET token_password=?, password_request=? WHERE id_usuario=?");
    if($sql->execute([$token,$user_id])){
        return $token;
    }
    return null;
}

function verificaTokenRequest($user_id,$token,$conn){
    $sql = $conn->prepare("SELECT id_usuario FROM usuario WHERE id_usuario=? AND token_password LIKE ? AND password_request=1 LIMIT 1");
    $sql->execute([$user_id,$token]);
    if($sql->fetchColumn() > 0){
        return true;
    }
    return false;
}

function cambioPassword($user_id,$password,$conn){
    $pass_hash = password_hash($password,PASSWORD_DEFAULT);
    $sql = $conn->prepare("UPDATE usuarios SET password=? WHERE id_usuario=?");
    if($sql->execute([$pass_hash,$user_id])){
        return true;
    }
    return null;
}
/* ******************************************************* */

?>