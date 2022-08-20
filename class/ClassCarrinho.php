<?php

/* Classe Carrinho */

//Inclusão da Classe de Conexão do Banco
include("ClassConexao.php");

class ClassCarrinho extends Conexao{
    //Define o mysqli para conexão com o banco
    public $mysqli;
    
    //Método construtor para iniciar sessões
    public function __construct(){
        session_start();
    }

    //Função para adicionar um produto ao carrinho (exige um produto vindo da Classe de Produtos)
    public function addProducts(ClassProdutos $product){
        //Instancia a conexão
        $this->mysqli = Conexao::getConection();

        //Pega codigo do usuario vindo da sessão de usuários
        $code_user = $_SESSION['usuario'];
            
        //Armazena as propriedades do produto através das funções da classe de Produtos
        $code_prod = $product->getCode();
        $desc_prod = $product->getDescription();
        $pric_prod = $product->getPrice();

        //Verificar se o produto existe no carrinho através de consulta no banco por contagem
        foreach($this->mysqli->query("SELECT COUNT(c.cd_carrinho) AS count_car 
        FROM tb_carrinho AS c 
            left join tb_interpretacao as i
            	on i.cd_interpretacao = c.cd_interpretacao
                	left join tb_servico as s
                    	on s.cd_servico = c.cd_servico
                            left join tb_instrumento as ins
                                on ins.cd_instrumento = c.cd_instrumento
                        	where i.nm_interpretacao = '$desc_prod' AND c.cd_usuario = '$code_user' AND c.nm_inativo = 0
                            OR s.nm_servico = '$desc_prod' AND c.cd_usuario = '$code_user' AND c.nm_inativo = 0
                            OR ins.nm_instrumento = '$desc_prod' AND c.cd_usuario = '$code_user' AND c.nm_inativo = 0") as $verifica_car){
            $count_car = $verifica_car['count_car'];
        }

        //Verificar se o produto existe no carrinho através de consulta no banco
        foreach($this->mysqli->query("SELECT c.cd_carrinho AS cd_car 
        FROM tb_carrinho AS c
            LEFT JOIN tb_interpretacao AS i
                ON i.cd_interpretacao = c.cd_interpretacao
                    LEFT JOIN tb_servico AS s
                        ON s.cd_servico = c.cd_servico
                            LEFT JOIN tb_instrumento AS ins
                                ON ins.cd_instrumento = c.cd_instrumento
            WHERE i.nm_interpretacao = '$desc_prod' AND c.cd_usuario = '$code_user' AND c.nm_inativo = 0
                OR s.nm_servico = '$desc_prod' AND c.cd_usuario = '$code_user' AND c.nm_inativo = 0
                OR ins.nm_instrumento = '$desc_prod' AND c.cd_usuario = '$code_user' AND c.nm_inativo = 0") as $verifica_car){
            $cd_car = $verifica_car['cd_car'];
        }

        //Se a primeira consulta for diferente de 0 -> coloca quantidade + 1
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
            foreach($this->mysqli->query("SELECT COUNT(cd_instrumento) AS codigo_instrumento FROM tb_instrumento WHERE cd_instrumento = '$code_prod' AND nm_instrumento = '$desc_prod' AND vl_instrumento = '$pric_prod'") as $verifica_instru){
                $cod_verif_inst = $verifica_instru['codigo_instrumento'];
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
            elseif($cod_verif_inst != 0){
                $insert_car = $this->mysqli->query("INSERT INTO tb_carrinho (cd_carrinho, qt_carrinho, cd_instrumento, cd_usuario, nm_tipo) VALUES ('$result_car', 1, '$code_prod', '$code_user', 3)");
            }
        }
    }

    //Limpar produtos
    public function clearProducts(){
        $this->mysqli = Conexao::getConection();

        //Pegar codigo do usuario
        $code_user = $_SESSION['usuario'];
        
        //Ao invés de simplesmente dar DELETE na tabela de carrinhos, muda para inativo (1 = inativo, 0 = ativo) -> porém não é o mais recomendado, o certo é mover para uma tabela de "apagados"
        $count_car = $this->mysqli->query("SELECT COUNT(cd_carrinho) AS cd_car FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = '$code_user'");
        $i = 0;
        do{
            $del_car = $this->mysqli->query("UPDATE tb_carrinho SET nm_inativo = 1 WHERE nm_inativo = 0 AND cd_usuario = '$code_user'");
        }
        while($i >= $count_car);
    }

    //Função para pegar a quantidade de produtos do carrinho
    public function getQuantity(){
        $this->mysqli = Conexao::getConection();

        //Pegar codigo do usuario
        $code_user = $_SESSION['usuario'];

        //Instancia a quantidade como 0
        $quantity = 0;

        //Verificar se há produtos no carrinho
        $verifica_prod = $this->mysqli->query("SELECT COUNT(cd_carrinho) FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = $code_user");

        //Se houver produtos no carrinho -> adicionar +1 no carrinho
        if($verifica_prod != 0){
            //Partituras
            foreach($this->mysqli->query("SELECT c.qt_carrinho AS qt_car FROM tb_carrinho AS c JOIN tb_interpretacao AS i ON i.cd_interpretacao = c.cd_interpretacao WHERE c.nm_inativo = 0 AND c.cd_usuario = '$code_user'") as $pega_quant){
                $quantity += $pega_quant['qt_car'];
            }

            //Serviços
            foreach($this->mysqli->query("SELECT c.qt_carrinho AS qt_car FROM tb_carrinho AS c JOIN tb_servico AS s ON s.cd_servico = c.cd_servico WHERE c.nm_inativo = 0 AND c.cd_usuario = '$code_user'") as $pega_quant){
                $quantity += $pega_quant['qt_car'];
            }

            //Instrumentos
            foreach($this->mysqli->query("SELECT c.qt_carrinho AS qt_car FROM tb_carrinho AS c JOIN tb_instrumento AS ins ON ins.cd_instrumento = c.cd_instrumento WHERE c.nm_inativo = 0 AND c.cd_usuario = '$code_user'") as $pega_quant){
                $quantity += $pega_quant['qt_car'];
            }
        }
        
        return $quantity;
    }

    //Listar produtos em forma de tabela
    public function listProducts(){
        $this->mysqli = Conexao::getConection();

        //Pegar codigo do usuario
        $code_user = $_SESSION['usuario'];

        $html="";

        //Mostrar Interpretações
        $confere_interp = $this->mysqli->query("SELECT COUNT(cd_carrinho) AS cd_car FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = '$code_user' AND cd_interpretacao != 0");
        //Se houver produtos no carrinho -> cria uma linha da tabela
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
                $html.="<div class='price'>R$" . str_replace('.',',',$product['vl_interp']) . "</div>";
                $html.="</td>";
                $html.="</tr>";
            }
        }
        
        //Mostrar Serviços
        $confere_serv = $this->mysqli->query("SELECT COUNT(cd_carrinho) AS cd_car FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = '$code_user' AND cd_servico != 0");
        //Se houver produtos no carrinho -> cria uma linha da tabela
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
                $html.="<div class='price'>R$" . str_replace('.',',',$product['vl_serv']) . "</div>";
                $html.="</td>";
                $html.="</tr>";
            }
        }

        //Mostrar Instrumentos
        $confere_inst = $this->mysqli->query("SELECT COUNT(cd_carrinho) AS cd_car FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = '$code_user' AND cd_instrumento != 0");
        //Se houver produtos no carrinho -> cria uma linha da tabela
        if($confere_inst != 0){
            foreach($this->mysqli->query("SELECT ins.nm_instrumento AS nm_inst, c.qt_carrinho AS qt_car, ins.vl_instrumento AS vl_inst, im.path AS pathh 
            FROM tb_carrinho AS c 
                JOIN tb_instrumento AS ins 
                    ON ins.cd_instrumento = c.cd_instrumento 
                        JOIN tb_imagem AS im 
                            ON im.cd_imagem = ins.cd_imagem 
                                WHERE c.nm_inativo = 0 AND c.cd_usuario = '$code_user' AND c.nm_tipo = 3") as $product){
                $html.="<tr>";
                $html.="<td>";
                $html.="<img src='../../" . $product['pathh'] . "' class='full-width'>";
                $html.="</td>";
                $html.="<td>";
                $html.="<br> <strong><span class='thin'>" . $product['nm_inst'] . "</span></strong>";
                $html.="<br> <span class='thin small'> Quantidade: " . $product['qt_car'] . "<br><br></span>";
                $html.="</td>";
                $html.="</tr>";
                $html.="<tr>";
                $html.="<td>";
                $html.="<div class='price'>R$" . $product['vl_inst'] . "</div>";
                $html.="</td>";
                $html.="</tr>";
            }
        }
        
        return $html;
    }

    //Método para pegar o valor do carrinho inteiro
    public function getAmount(){
        $this->mysqli = Conexao::getConection();

        //Pegar codigo do usuario
        $code_user = $_SESSION['usuario'];

        //Instancia o total (amount) como 0
        $amount = 0;

        //Verificar se há produtos no carrinho
        $verifica_prod = $this->mysqli->query("SELECT COUNT(cd_carrinho) FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = $code_user");

        //Se houver -> pega quantidade e valor de produtos
        if($verifica_prod != 0){
            //Pegar quantidade e valor
            foreach($this->mysqli->query("SELECT c.qt_carrinho AS qt_car, i.vl_interpretacao AS vl_interp FROM tb_carrinho AS c JOIN tb_interpretacao AS i ON i.cd_interpretacao = c.cd_interpretacao WHERE c.nm_inativo = 0 AND c.cd_usuario = '$code_user'") as $quant_prod){
                $qt_car = $quant_prod['qt_car'];
                $vl_interp = $quant_prod['vl_interp'];
                //Faz a conta para totalizar o amount
                $amount += floor(($qt_car * $vl_interp) * 100) /100;
            }
            foreach($this->mysqli->query("SELECT c.qt_carrinho AS qt_car, s.vl_servico AS vl_serv FROM tb_carrinho AS c JOIN tb_servico AS s ON s.cd_servico = c.cd_servico WHERE c.nm_inativo = 0 AND c.cd_usuario = '$code_user'") as $quant_serv){
                $qt_car = $quant_serv['qt_car'];
                $vl_serv = $quant_serv['vl_serv'];
                //Faz a conta para totalizar o amount
                $amount += floor(($qt_car * $vl_serv) * 100) / 100;
            }
            foreach($this->mysqli->query("SELECT c.qt_carrinho AS qt_car, replace(ins.vl_instrumento, ',', '') AS vl_inst FROM tb_carrinho AS c JOIN tb_instrumento AS ins ON ins.cd_instrumento = c.cd_instrumento WHERE c.nm_inativo = 0 AND c.cd_usuario = '$code_user'") as $quant_inst){
                $qt_car = $quant_inst['qt_car'];
                $vl_inst = $quant_inst['vl_inst'];
                //Faz a conta para totalizar o amount
                $amount += floor(($qt_car * $vl_inst) * 100) / 100;
            }
        }

        return $amount;
    }
}

?>