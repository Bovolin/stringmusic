<?php
require("../../lib/vendor/autoload.php");

$carrinho= new \Classes\ClassCarrinho();
if($_GET['action'] == 'add'){
    $product = new \Classes\ClassProdutos($_GET['id'],$_GET['product'],$_GET['price']);
    $carrinho->addProducts($product);
}

elseif($_GET['action'] == 'clear'){
    $carrinho->clearProducts();
}

header("Location: ../view/mercadopag.php")

?>