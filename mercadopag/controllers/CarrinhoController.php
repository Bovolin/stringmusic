<?php
/* Controller do Carrinho */

//Carrega o autoload do composer
require("../../lib/vendor/autoload.php");

//Inclui a Classe Carrinho
include("../../class/ClassCarrinho.php");
//Inclui a Classe Produto
include("../../class/ClassProdutos.php");

//Instancia um novo carrinho
$carrinho = new ClassCarrinho();

//Caso houver um GET do botão 'add' -> envia um novo produto para classe Carrinho
if($_GET['action'] == 'add'){
    //Instancia um novo produto com as informações do GET
    $product = new ClassProdutos($_GET['product'],$_GET['price'],$_GET['code']);
    //Coloca o produto no carrinho através da função da Classe Carrinho
    $carrinho->addProducts($product);
}
//Se houver um GET vindo do botão 'clear' -> reseta o carrinho inteiro
elseif($_GET['action'] == 'clear'){
    //Ativa o método (função) de limpar o carrinho
    $carrinho->clearProducts();
}

//Redireciona o usuário para a página de carrinho/compra
header("Location: ../view/mercadopag.php");

?>