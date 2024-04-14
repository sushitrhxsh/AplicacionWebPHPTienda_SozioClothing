<?php 
class Database{
    private $hostname ="localhost";
    private $username = "root";
    private $password = "";
    private $database = "tienda";
    private $charset = "utf8";
    
    function conectar(){
        try {
            $conexion = "mysql:host=". $this->hostname.";dbname=".$this->database .";charset=".$this->charset;

            $options = [  
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            $pdo = new PDO($conexion,$this->username,$this->password,$options);
            return $pdo;
            
        } catch(PDOException $e) {
            echo 'error conexion: ' . $e->getMessage();
            exit;
        }
    }
}


?>