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
        foreach($this->mysqli->query("SELECT COUNT(c.cd_carrinho) AS count_car 
        FROM tb_carrinho AS c 
            left join tb_interpretacao as i
            	on i.cd_interpretacao = c.cd_interpretacao
                	left join tb_servico as s
                    	on s.cd_servico = c.cd_servico
                        	where i.nm_interpretacao = '$desc_prod' AND c.cd_usuario = 2 AND c.nm_inativo = 0
                OR s.nm_servico = '$desc_prod' AND c.cd_usuario = 2 AND c.nm_inativo = 0") as $verifica_car){
            $count_car = $verifica_car['count_car'];
        }

        foreach($this->mysqli->query("SELECT c.cd_carrinho AS cd_car 
        FROM tb_carrinho AS c
            LEFT JOIN tb_interpretacao AS i
                ON i.cd_interpretacao = c.cd_interpretacao
                    LEFT JOIN tb_servico AS s
                        ON s.cd_servico = c.cd_servico
            WHERE i.nm_interpretacao = '$desc_prod' AND c.cd_usuario = '$code_user' AND c.nm_inativo = 0
                OR s.nm_servico = '$desc_prod' AND c.cd_usuario = '$code_user' AND c.nm_inativo = 0") as $verifica_car){
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
            foreach($this->mysqli->query("SELECT COUNT(cd_interpretacao) AS codigo_interpretacao FROM tb_interpretacao WHERE cd_interpretacao = '$code_prod' AND nm_interpretacao = '$desc_prod' AND vl_interpretacao = '$pric_prod'") as $verifica_interp){
                $cod_verif_interp = $verifica_interp['codigo_interpretacao'];
            }
            foreach($this->mysqli->query("SELECT COUNT(cd_servico) AS codigo_servico FROM tb_servico WHERE cd_servico = '$code_prod' AND nm_servico = '$desc_prod' AND vl_servico = '$pric_prod'") as $verifica_servic){
                $cod_verif_serv = $verifica_servic['codigo_servico'];
            }
            
            //Contador carrinho
            $contador_car = $this->mysqli->query("SELECT COUNT(cd_carrinho) AS carrinho FROM tb_carrinho");
            $contador_car = $contador_car->fetch_assoc();
            $result_car = $contador_car['carrinho'] + 1;

            //Inserir na tabela carrinho
            if($cod_verif_interp != 0){
                $insert_car = $this->mysqli->query("INSERT INTO tb_carrinho (cd_carrinho, qt_carrinho, cd_interpretacao, cd_usuario, nm_tipo) VALUES ('$result_car', 1, '$code_prod', '$code_user', 1)");
            }
            elseif($cod_verif_serv != 0){
                $insert_car = $this->mysqli->query("INSERT INTO tb_carrinho (cd_carrinho, qt_carrinho, cd_servico, cd_usuario, nm_tipo) VALUES ('$result_car', 1, '$code_prod', '$code_user', 2)");
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
        $this->mysqli = Conexao::getConection();

        //Pegar codigo do usuario
        $code_user = $_SESSION['usuario'];

        $html="";

        //Mostrar Interpretações
        $confere_interp = $this->mysqli->query("SELECT COUNT(cd_carrinho) AS cd_car FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = '$code_user' AND cd_interpretacao != 0");
        if($confere_interp != 0){
            foreach($this->mysqli->query("SELECT im.path AS pathh, i.nm_interpretacao AS nm_interp, c.qt_carrinho AS qt_car, i.vl_interpretacao AS vl_interp FROM tb_carrinho AS c JOIN tb_interpretacao AS i ON i.cd_interpretacao = c.cd_interpretacao JOIN tb_imagem AS im ON im.cd_imagem = i.cd_imagem WHERE c.nm_inativo = 0 AND c.cd_usuario = '$code_user' AND c.nm_tipo = 1") as $product){
                $html.="<tr>";
                $html.="<td>";
                $html.="<img src='../../" . $product['pathh'] . "' class='full-width'>";
                $html.="</td>";
                $html.="<td>";
                $html.="<br> <strong><span class='thin'>" . $product['nm_interp'] . "</span></strong>";
                $html.="<br> <span class='thin small'> Quantidade: " . $product['qt_car'] . "<br><br></span>";
                $html.="</td>";
                $html.="</tr>";
                $html.="<tr>";
                $html.="<td>";
                $html.="<div class='price'>R$" . $product['vl_interp'] . "</div>";
                $html.="</td>";
                $html.="</tr>";
            }
        }
        
        //Mostrar Serviços
        $confere_serv = $this->mysqli->query("SELECT COUNT(cd_carrinho) AS cd_car FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = '$code_user' AND cd_servico != 0");
        if($confere_serv != 0){
            foreach($this->mysqli->query("SELECT s.nm_servico AS nm_serv, c.qt_carrinho AS qt_car, s.vl_servico AS vl_serv, im.path AS pathh 
            FROM tb_carrinho AS c 
                JOIN tb_servico AS s 
                    ON s.cd_servico = c.cd_servico 
                        JOIN tb_imagem AS im 
                            ON im.cd_imagem = s.cd_imagem 
                                WHERE c.nm_inativo = 0 AND c.cd_usuario = '$code_user' AND c.nm_tipo = 2") as $product){
                $html.="<tr>";
                $html.="<td>";
                $html.="<img src='../../" . $product['pathh'] . "' class='full-width'>";
                $html.="</td>";
                $html.="<td>";
                $html.="<br> <strong><span class='thin'>" . $product['nm_serv'] . "</span></strong>";
                $html.="<br> <span class='thin small'> Quantidade: " . $product['qt_car'] . "<br><br></span>";
                $html.="</td>";
                $html.="</tr>";
                $html.="<tr>";
                $html.="<td>";
                $html.="<div class='price'>R$" . $product['vl_serv'] . "</div>";
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