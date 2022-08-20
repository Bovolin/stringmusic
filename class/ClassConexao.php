<?php

/* Classe de Conexão */

/* Nota: essa classe serve apenas para fazer conexão com o banco no meio de outras classes */

class Conexao{

    private static $con;

    //Função para definir os padrões da conexão
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