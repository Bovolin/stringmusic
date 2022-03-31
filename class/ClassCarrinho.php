<?php

include("ClassConexao.php");

class ClassCarrinho extends Conexao{

    public $mysqli;
    
    public function __construct(){
        session_start();
    }

    //Add produtos
    public function addProducts(ClassProdutos $product){
        $this->mysqli = Conexao::getConection();

        //Pegar codigo do usuario
        $code_user = $_SESSION['usuario'];
            
        //Armazena produto em variáveis
        $code_prod = $product->getCode();
        $desc_prod = $product->getDescription();
        $pric_prod = $product->getPrice();

        //Verificar se o produto existe no carrinho
        foreach($this->mysqli->query("SELECT COUNT(cd_carrinho) AS count_car, cd_carrinho AS cd_car FROM tb_carrinho WHERE cd_interpretacao = '$code_prod' OR cd_servico ='$code_prod'") as $verifica_car){
            $count_car = $verifica_car['count_car'];
            $cd_car = $verifica_car['cd_car'];
        }

        if($count_car != 0){
            //Pegar quantidade do carrinho
            foreach($this->mysqli->query("SELECT qt_carrinho AS qt_car FROM tb_carrinho WHERE cd_carrinho = '$cd_car'") as $quantidade_car){
                $qt_car = $quantidade_car['qt_car'] + 1;
            }
            //Adicionar +1 na quantidade do produto
            $altera_car = $this->mysqli->query("UPDATE tb_carrinho SET qt_carrinho = '$qt_car' WHERE cd_carrinho = '$cd_car'");
        }
        else{
            //Verificar tipo de produto
            $verifica_interp = $this->mysqli->query("SELECT COUNT(cd_interpretacao) AS codigo_interpretacao FROM tb_interpretacao WHERE cd_interpretacao = '$code_prod' AND nm_interpretacao = '$desc_prod' AND vl_interpretacao = '$pric_prod'");
            $verifica_servic = $this->mysqli->query("SELECT COUNT(cd_servico) AS codigo_servico FROM tb_servico WHERE cd_servico = '$code_prod' AND nm_servico = '$desc_prod' AND vl_servico = '$pric_prod'");
            
            //Contador carrinho
            $contador_car = $this->mysqli->query("SELECT COUNT(cd_carrinho) AS carrinho FROM tb_carrinho");
            $contador_car = $contador_car->fetch_assoc();
            $result_car = $contador_car['carrinho'] + 1;

            //Inserir na tabela carrinho
            if($verifica_interp != 0){
                $insert_car = $this->mysqli->query("INSERT INTO tb_carrinho (cd_carrinho, qt_carrinho, cd_interpretacao, cd_usuario) VALUES ('$result_car', 1, '$code_prod', '$code_user')");
            }
            elseif($verifica_servic != 0){
                $insert_car = $this->mysqli->query("INSERT INTO tb_carrinho (cd_carrinho, qt_carrinho, cd_servico, cd_usuario) VALUES ('$result_car', 1, '$code_prod', '$code_user')");
            }
        }
    }

    //Limpar produtos
    public function clearProducts(){
        $this->mysqli = Conexao::getConection();

        //Pegar codigo do usuario
        $code_user = $_SESSION['usuario'];
        
        //Mudar todos os produtos para nm_inativo para 1
        $count_car = $this->mysqli->query("SELECT COUNT(cd_carrinho) AS cd_car FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = '$code_user'");
        $i = 0;
        do{
            $del_car = $this->mysqli->query("UPDATE tb_carrinho SET nm_inativo = 1 WHERE nm_inativo = 0 AND cd_usuario = '$code_user'");
        }
        while($i >= $count_car);
    }

    //Contar produtos
    public function getQuantity(){
        $quantity = 0;
        if(isset($_SESSION['products'])){
            foreach($_SESSION['products'] as $product){
                $quantity += $product['quantity'];
            }
        }
        return $quantity;
    }

    //Listar produtos
    public function listProducts(){
        $html="";
        if(isset($_SESSION['products'])){
            foreach($_SESSION['products'] as $product){
                $html.="<tr>";
                $html.="<td>";
                $html.="<br> <strong><span class='thin'>" . $product['description'] . "</span></strong>";
                $html.="<br> <span class='thin small'> Quantidade: " . $product['quantity'] . "<br><br></span>";
                $html.="</td>";
                $html.="</tr>";
                $html.="<tr>";
                $html.="<td>";
                $html.="<div class='price'>R$" . $product['price'] . "</div>";
                $html.="</td>";
                $html.="</tr>";
            } 
        }
        
        return $html;
    }

    //Amount produtos
    public function getAmount(){
        $amount = 0;
        if(isset($_SESSION['products'])){
           foreach($_SESSION['products'] as $product){
                $amount += $product['quantity'] * $product['price'];
            } 
        }
        return $amount;
    }
}

?>