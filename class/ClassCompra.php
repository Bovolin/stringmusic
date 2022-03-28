<?php

include("ClassConexao.php");

class Comprar extends Conexao{

    public $valor_produto;
    public $mysqli;

    public function get_Value($tb_name, $codigo_produto, $quantidade_produto){

        $this->mysqli = Conexao::getConection();

        foreach($this->mysqli->query("SELECT vl_".$tb_name." AS valor FROM tb_".$tb_name." WHERE cd_".$tb_name."= '$codigo_produto'") as $retorno){
            $valor = $retorno['valor'];
        }

        $valor *= $quantidade_produto;

        return $valor;

    }

}

?>