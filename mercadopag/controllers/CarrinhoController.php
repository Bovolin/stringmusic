<?php
require("../../lib/vendor/autoload.php");

include("../../class/ClassCarrinho.php");
include("../../class/ClassProdutos.php");

$carrinho = new ClassCarrinho();

if($_GET['action'] == 'add'){
    $product = new ClassProdutos($_GET['product'],$_GET['price'],$_GET['code']);
    $carrinho->addProducts($product);
}
elseif($_GET['action'] == 'clear'){
    $carrinho->clearProducts();
}

header("Location: ../view/mercadopag.php");

?>