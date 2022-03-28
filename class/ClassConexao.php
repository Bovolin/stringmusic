<?php

class Conexao{

    private static $con;

    public static function getConection(){
        define('HOST', 'localhost');
        define('USUARIO', 'root');
        define('SENHA', '');
        define('DB', 'stringmusic');
        self::$con = mysqli_connect(HOST, USUARIO, SENHA, DB) or die ('Não foi possível conectar!');

        return self::$con;
    }

}

?>