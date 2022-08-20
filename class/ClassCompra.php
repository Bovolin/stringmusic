<?php

/* Classe de Compras */

/* Nota: essa classe só possue 1 função que serve para pegar o valor da compra inteira */

//Inclusão da Conexão
include("ClassConexao.php");

class Comprar extends Conexao{

    public $valor_produto;
    public $mysqli;

    //Método para pegar o valor (recebe o nome da tabela, o código e a quantidade do produto)
    public function get_Value($tb_name, $codigo_produto, $quantidade_produto){

        $this->mysqli = Conexao::getConection();

        //Faz o select dos produtos
        foreach($this->mysqli->query("SELECT vl_".$tb_name." AS valor FROM tb_".$tb_name." WHERE cd_".$tb_name."= '$codigo_produto'") as $retorno){
            $valor = $retorno['valor'];
        }

        //Adicionar o valor total
        $valor *= $quantidade_produto;

        return $valor;

    }

}

?>